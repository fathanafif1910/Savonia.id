<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $orders = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with(['items.product', 'user'])
            ->latest()
            ->get();

        $totalRevenue = $orders->where('payment_status', 'paid')->sum('grand_total');
        $totalOrders = $orders->count();
        $paidOrdersCount = $orders->where('payment_status', 'paid')->count();
        $totalItemsSold = $orders->where('payment_status', 'paid')->sum(function ($order) {
            return $order->items->sum('quantity');
        });

        return view('admin.reports.index', compact(
            'orders',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'paidOrdersCount',
            'totalItemsSold'
        ));
    }

    public function print(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $orders = Order::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->with(['items.product', 'user'])
            ->latest()
            ->get();

        $totalRevenue = $orders->where('payment_status', 'paid')->sum('grand_total');
        $paidOrdersCount = $orders->where('payment_status', 'paid')->count();

        return view('admin.reports.print', compact(
            'orders',
            'startDate',
            'endDate',
            'totalRevenue',
            'paidOrdersCount'
        ));
    }
}
