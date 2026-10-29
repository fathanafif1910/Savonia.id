<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, $invoice_number)
    {
        $order = Order::where('invoice_number', $invoice_number)
            ->with(['items.product', 'user'])
            ->firstOrFail();

        // Allow owner or admin or public if tracking
        if (Auth::check() && ! Auth::user()->isAdmin() && $order->user_id !== Auth::id()) {
            abort(403, 'Akses pesanan tidak diizinkan.');
        }

        // If unpaid, verify with Midtrans API directly (handles localhost redirect where webhook cannot reach)
        if (! $order->isPaid() && $order->snap_token && ! str_starts_with($order->snap_token, 'mock-snap-')) {
            try {
                Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
                Config::$isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));

                $midtransStatus = Transaction::status($order->invoice_number);
                $transStatus = is_object($midtransStatus) ? ($midtransStatus->transaction_status ?? null) : null;

                if (in_array($transStatus, ['capture', 'settlement'])) {
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'paid',
                        'payment_type' => $midtransStatus->payment_type ?? 'midtrans',
                        'midtrans_transaction_id' => $midtransStatus->transaction_id ?? null,
                        'paid_at' => now(),
                    ]);
                    $order->refresh();
                }
            } catch (\Exception $e) {
                // Silently ignore if transaction not yet submitted in Midtrans
            }
        }

        return view('orders.show', compact('order'));
    }

    public function track(Request $request)
    {
        $order = null;
        if ($request->filled('invoice')) {
            $order = Order::where('invoice_number', trim($request->invoice))
                ->with(['items.product'])
                ->first();
        }

        return view('orders.track', compact('order'));
    }

    public function simulateSuccess($invoice_number)
    {
        $order = Order::where('invoice_number', $invoice_number)->firstOrFail();

        $order->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'payment_type' => 'midtrans_sandbox_simulation',
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.show', $order->invoice_number)
            ->with('success', 'Pembayaran Midtrans Sandbox Berhasil Diverifikasi! Pesanan Anda sedang dipersiapkan.');
    }

    public function invoice($invoice_number)
    {
        $order = Order::where('invoice_number', $invoice_number)
            ->with(['items.product', 'user'])
            ->firstOrFail();

        return view('orders.invoice', compact('order'));
    }

    /**
     * API endpoint untuk polling payment status dari Midtrans
     * Digunakan oleh JavaScript polling di halaman payment
     */
    public function checkPaymentStatus($invoice_number)
    {
        $order = Order::where('invoice_number', $invoice_number)->firstOrFail();

        // Allow owner or admin only
        if (Auth::check() && ! Auth::user()->isAdmin() && $order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If already paid, return immediately
        if ($order->isPaid()) {
            return response()->json([
                'status' => 'paid',
                'payment_status' => 'paid',
                'message' => 'Pembayaran sudah terverifikasi',
            ]);
        }

        // If unpaid, query Midtrans API
        if ($order->snap_token && ! str_starts_with($order->snap_token, 'mock-snap-')) {
            try {
                Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
                Config::$isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));

                $midtransStatus = Transaction::status($order->invoice_number);
                $transStatus = is_object($midtransStatus) ? ($midtransStatus->transaction_status ?? null) : null;

                \Log::info('Midtrans check payment', [
                    'invoice' => $invoice_number,
                    'transaction_status' => $transStatus,
                    'payment_type' => $midtransStatus->payment_type ?? null,
                    'midtrans_response' => json_encode($midtransStatus),
                ]);

                // Jika pembayaran sudah diterima di Midtrans, update order
                if (in_array($transStatus, ['capture', 'settlement'])) {
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => 'paid',
                        'payment_type' => $midtransStatus->payment_type ?? 'midtrans',
                        'midtrans_transaction_id' => $midtransStatus->transaction_id ?? null,
                        'paid_at' => now(),
                    ]);

                    \Log::info('Order payment updated to PAID', ['order_id' => $order->id, 'invoice' => $invoice_number]);

                    return response()->json([
                        'status' => 'paid',
                        'payment_status' => 'paid',
                        'message' => 'Pembayaran berhasil terverifikasi dari Midtrans!',
                    ]);
                }

                // Payment masih pending
                return response()->json([
                    'status' => 'unpaid',
                    'payment_status' => $transStatus,
                    'message' => 'Pembayaran masih diproses (' . $transStatus . ')...',
                ]);

            } catch (\Exception $e) {
                \Log::error('Midtrans payment check error', [
                    'invoice' => $invoice_number,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // Return current status jika query Midtrans error
                return response()->json([
                    'status' => $order->payment_status,
                    'payment_status' => $order->payment_status,
                    'message' => 'Error checking: ' . $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'status' => $order->payment_status,
            'payment_status' => $order->payment_status,
            'message' => 'Order status: ' . $order->payment_status,
        ]);
    }
}
