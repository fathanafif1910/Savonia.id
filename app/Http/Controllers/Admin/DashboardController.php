<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Orders by Status
        $ordersPending = Order::where('status', 'pending')->count();
        $ordersPaid = Order::where('status', 'paid')->count();
        $ordersProcessing = Order::where('status', 'processing')->count();
        $ordersShipped = Order::where('status', 'shipped')->count();
        $ordersCompleted = Order::where('status', 'completed')->count();

        // Low stock products alert (< 25)
        $lowStockProducts = Product::where('stock', '<=', 25)
            ->with('category')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Recent Orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(6)
            ->get();

        // Chart Data (Last 6 Months Revenue)
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->selectRaw('DATE_FORMAT(created_at, "%b %Y") as month_label, SUM(grand_total) as total')
            ->groupBy('month_label')
            ->orderByRaw('MIN(created_at) ASC')
            ->take(6)
            ->get();

        $chartLabels = $monthlyRevenue->pluck('month_label')->toArray();
        $chartData = $monthlyRevenue->pluck('total')->toArray();

        if (empty($chartLabels)) {
            $chartLabels = [date('M Y')];
            $chartData = [$totalRevenue];
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'ordersPending',
            'ordersPaid',
            'ordersProcessing',
            'ordersShipped',
            'ordersCompleted',
            'lowStockProducts',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }
}
