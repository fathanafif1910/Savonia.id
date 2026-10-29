<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product.category')
                ->get();
        } else {
            $sessionCart = session()->get('cart', []);
            $cartItems = collect();
            foreach ($sessionCart as $productId => $item) {
                $product = Product::find($productId);
                if ($product) {
                    $cartObj = new Cart([
                        'user_id' => null,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                    $cartObj->id = $productId; // temporary id for guest
                    $cartObj->setRelation('product', $product);
                    $cartItems->push($cartObj);
                }
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->final_price * $item->quantity : 0;
        });

        $totalWeight = $cartItems->sum(function ($item) {
            return $item->product ? ($item->product->weight_gram ?: 100) * $item->quantity : 0;
        });

        return view('cart.index', compact('cartItems', 'subtotal', 'totalWeight'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty = (int) $request->quantity;

        if ($qty > $product->stock) {
            return back()->with('error', 'Maaf, stok aksesoris tidak mencukupi.');
        }

        if (Auth::check()) {
            $existing = Cart::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($existing) {
                $newQty = $existing->quantity + $qty;
                if ($newQty > $product->stock) {
                    return back()->with('error', 'Jumlah melebihi stok yang tersedia.');
                }
                $existing->update(['quantity' => $newQty]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $qty,
                ]);
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['quantity'] += $qty;
            } else {
                $cart[$product->id] = [
                    'quantity' => $qty,
                    'notes' => $request->notes ?? null,
                ];
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Aksesoris berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $qty = (int) $request->quantity;

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
            if ($qty > $cart->product->stock) {
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }
            $cart->update(['quantity' => $qty]);
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $product = Product::find($id);
                if ($product && $qty > $product->stock) {
                    return back()->with('error', 'Jumlah melebihi stok tersedia.');
                }
                $cart[$id]['quantity'] = $qty;
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($id)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
