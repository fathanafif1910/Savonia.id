<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('invoice_number', 'like', "%{$q}%")
                    ->orWhere('recipient_name', 'like', "%{$q}%")
                    ->orWhere('tracking_number', 'like', "%{$q}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,expired,failed,refunded',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $data = [
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'tracking_number' => $request->tracking_number,
        ];

        if ($request->payment_status === 'paid' && !$order->paid_at) {
            $data['paid_at'] = now();
        }

        if ($request->status === 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = now();
        }

        if ($request->status === 'completed' && !$order->completed_at) {
            $data['completed_at'] = now();
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan dan nomor resi berhasil diperbarui!');
    }
}
