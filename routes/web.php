<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes — Savonia.id E-Commerce
|--------------------------------------------------------------------------
*/

// ─── Public Routes ─────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog (Product Browsing)
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// Order Tracking (Public — no login needed)
Route::get('/lacak-pesanan', [OrderController::class, 'track'])->name('orders.track');

// ─── Authentication ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Cart (Accessible with or without login) ──────────────────
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{id}', [CartController::class, 'remove'])->name('cart.remove');

// ─── Customer Authenticated Routes ────────────────────────────
Route::middleware('auth')->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders (Customer)
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{invoice_number}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{invoice_number}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    
    // API: Check payment status (for polling)
    Route::get('/api/pesanan/{invoice_number}/check-payment', [OrderController::class, 'checkPaymentStatus'])->name('orders.check-payment');

    // Midtrans Sandbox Payment Simulation
    Route::post('/pesanan/{invoice_number}/simulate-success', [OrderController::class, 'simulateSuccess'])->name('orders.simulate');
});

// ─── Midtrans Webhook Callback (CSRF excluded in bootstrap/app.php) ──
Route::post('/midtrans/callback', function (\Illuminate\Http\Request $request) {
    $serverKey = env('MIDTRANS_SERVER_KEY');
    $payload = $request->all();

    $orderId = $payload['order_id'] ?? null;
    $statusCode = $payload['status_code'] ?? null;
    $grossAmount = $payload['gross_amount'] ?? null;
    $signatureKey = $payload['signature_key'] ?? null;
    $transactionStatus = $payload['transaction_status'] ?? null;

    // Verify Signature
    $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    if ($signatureKey !== $expectedSignature) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    $order = \App\Models\Order::where('invoice_number', $orderId)->first();
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Update order based on Midtrans status
    if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
        $order->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'payment_type' => $payload['payment_type'] ?? 'midtrans',
            'paid_at' => now(),
        ]);
    } elseif ($transactionStatus === 'pending') {
        $order->update([
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
        $order->update([
            'status' => 'cancelled',
            'payment_status' => $transactionStatus === 'expire' ? 'expired' : 'failed',
        ]);
    }

    return response()->json(['message' => 'OK']);
})->name('midtrans.callback');

// ─── Admin Panel Routes ────────────────────────────────────────
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    // Reports (Laporan Penjualan)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
});
