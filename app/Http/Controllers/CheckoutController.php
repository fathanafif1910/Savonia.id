<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product.category')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanjamu masih kosong.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->final_price * $item->quantity : 0;
        });

        $totalWeight = $cartItems->sum(function ($item) {
            return $item->product ? ($item->product->weight_gram ?: 100) * $item->quantity : 0;
        });

        $user = Auth::user();

        return view('checkout.index', compact('cartItems', 'subtotal', 'totalWeight', 'user'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:100',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_courier' => 'required|string',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanjamu kosong.');
        }

        // Calculate Subtotal & Shipping Cost
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->final_price * $item->quantity : 0;
        });

        $shippingCourier = $request->shipping_courier;
        $cityLower = strtolower($request->shipping_city);

        if (str_contains($shippingCourier, 'Ambil di Toko') || str_contains($shippingCourier, 'Self Pick-up')) {
            $shippingCost = 0;
        } elseif (str_contains($shippingCourier, 'Grab')) {
            // Grab Express gratis untuk wilayah Kota Banda Aceh
            $shippingCost = 0;
        } else {
            // J&T Express Reguler (Sistem Tarif Berdasarkan Daerah)
            if (str_contains($cityLower, 'aceh besar') || str_contains($cityLower, 'sabang')) {
                $shippingCost = 15000;
            } elseif (str_contains($cityLower, 'aceh') || str_contains($cityLower, 'lhokseumawe') || str_contains($cityLower, 'langsa') || str_contains($cityLower, 'bireuen') || str_contains($cityLower, 'pidie') || str_contains($cityLower, 'meulaboh') || str_contains($cityLower, 'takengon')) {
                $shippingCost = 18000;
            } elseif (str_contains($cityLower, 'medan') || str_contains($cityLower, 'sumut') || str_contains($cityLower, 'sumatera utara') || str_contains($cityLower, 'deli')) {
                $shippingCost = 25000;
            } elseif (str_contains($cityLower, 'jakarta') || str_contains($cityLower, 'jawa') || str_contains($cityLower, 'bandung') || str_contains($cityLower, 'surabaya') || str_contains($cityLower, 'semarang') || str_contains($cityLower, 'tangerang') || str_contains($cityLower, 'bekasi') || str_contains($cityLower, 'bogor')) {
                $shippingCost = 35000;
            } else {
                $shippingCost = 22000;
            }
        }

        $grandTotal = $subtotal + $shippingCost;

        // Generate Unique Invoice Code
        $invoiceNumber = 'INV-SAV-'.date('Ymd').'-'.strtoupper(Str::random(5));

        // Create Order
        $order = Order::create([
            'invoice_number' => $invoiceNumber,
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_courier' => $request->shipping_courier,
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_postal_code' => $request->shipping_postal_code,
            'customer_notes' => $request->customer_notes,
        ]);

        // Create Order Items & Update Stock
        $itemDetails = [];
        foreach ($cartItems as $cart) {
            if ($cart->product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_name' => $cart->product->name,
                    'product_sku' => $cart->product->sku,
                    'product_image' => $cart->product->image,
                    'price' => $cart->product->final_price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->product->final_price * $cart->quantity,
                ]);

                // Deduct stock
                $cart->product->decrement('stock', $cart->quantity);

                // Add to Midtrans item details
                $itemDetails[] = [
                    'id' => (string) $cart->product_id,
                    'price' => (int) $cart->product->final_price,
                    'quantity' => (int) $cart->quantity,
                    'name' => substr($cart->product->name, 0, 50),
                ];
            }
        }

        // Add Shipping cost as line item in Midtrans
        if ($shippingCost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $shippingCost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim ('.$request->shipping_courier.')',
            ];
        }

        // Configure Midtrans
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized', env('MIDTRANS_IS_SANITIZED', true));
        Config::$is3ds = (bool) config('services.midtrans.is_3ds', env('MIDTRANS_IS_3DS', true));

        $params = [
            'transaction_details' => [
                'order_id' => $order->invoice_number,
                'gross_amount' => (int) $grandTotal,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'email' => Auth::user()->email,
                'phone' => $order->recipient_phone,
                'shipping_address' => [
                    'first_name' => $order->recipient_name,
                    'address' => $order->shipping_address,
                    'city' => $order->shipping_city,
                    'postal_code' => $order->shipping_postal_code,
                ],
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('orders.show', $order->invoice_number),
                'error' => route('orders.show', $order->invoice_number),
                'pending' => route('orders.show', $order->invoice_number),
            ],
        ];

        // Generate Snap Token
        try {
            $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
            if ($serverKey && ! str_contains($serverKey, 'TEST-KEY')) {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
            } else {
                $snapToken = 'mock-snap-'.Str::random(24);
                $order->update(['snap_token' => $snapToken]);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Snap Error: '.$e->getMessage());
            $snapToken = 'mock-snap-'.Str::random(24);
            $order->update(['snap_token' => $snapToken]);
        }

        // Clear user cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.show', $order->invoice_number)->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }
}
