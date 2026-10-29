@extends('layouts.admin')

@section('title', 'Ringkasan Statistik Penjualan')

@section('content')
<div style="margin-bottom: 28px;">
    <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 4px;">
        Dashboard Penjualan Savonia.id
    </h1>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
        Ringkasan analitik operasional, pendapatan e-commerce, dan status pengiriman produk aksesoris.
    </p>
</div>

<!-- 4 TOP STAT CARDS -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-bottom: 28px;">
    <!-- Revenue -->
    <div class="card" style="margin-bottom: 0; background: linear-gradient(135deg, #FF6600 0%, #EE1D23 100%); color: white; border: none; box-shadow: 0 10px 20px rgba(255, 102, 0, 0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">Total Omzet Lunas</span>
            <div style="background: rgba(255,255,255,0.2); width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="wallet" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 800;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div style="font-size: 0.78rem; opacity: 0.85; margin-top: 4px;">Terverifikasi via Midtrans</div>
    </div>

    <!-- Total Orders -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Transaksi</span>
            <div style="background: #EFF6FF; color: #2563EB; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="shopping-cart" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy);">{{ $totalOrders }}</div>
        <div style="font-size: 0.78rem; color: #10B981; margin-top: 4px; font-weight: 600;">
            {{ $ordersCompleted }} pesanan berhasil terkirim
        </div>
    </div>

    <!-- Total Products -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Produk Aksesoris</span>
            <div style="background: #FEF3C7; color: #D97706; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="package" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy);">{{ $totalProducts }}</div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Koleksi aktif di etalase</div>
    </div>

    <!-- Total Customers -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Pelanggan Terdaftar</span>
            <div style="background: #ECFDF5; color: #059669; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <i data-lucide="users" style="width: 20px; height: 20px;"></i>
            </div>
        </div>
        <div style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy);">{{ $totalCustomers }}</div>
        <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Akun pembeli aktif</div>
    </div>
</div>

<!-- ORDER STATUS BREAKDOWN PILLS -->
<div class="card" style="padding: 18px 24px;">
    <div style="font-size: 0.85rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 12px;">
        Distribusi Status Pesanan:
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" style="background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; padding: 6px 14px; border-radius: 9999px; font-size: 0.825rem; font-weight: 700; text-decoration: none;">
            ⏳ Menunggu Bayar: {{ $ordersPending }}
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" style="background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; padding: 6px 14px; border-radius: 9999px; font-size: 0.825rem; font-weight: 700; text-decoration: none;">
            💳 Lunas: {{ $ordersPaid }}
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" style="background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; padding: 6px 14px; border-radius: 9999px; font-size: 0.825rem; font-weight: 700; text-decoration: none;">
            📦 Diproses / Kemas: {{ $ordersProcessing }}
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" style="background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; padding: 6px 14px; border-radius: 9999px; font-size: 0.825rem; font-weight: 700; text-decoration: none;">
            🚚 Dalam Pengiriman: {{ $ordersShipped }}
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" style="background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; padding: 6px 14px; border-radius: 9999px; font-size: 0.825rem; font-weight: 700; text-decoration: none;">
            ✅ Selesai: {{ $ordersCompleted }}
        </a>
    </div>
</div>

<!-- 2-COLUMNS: CHART & LOW STOCK ALERT -->
<div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px; margin-bottom: 24px;">
    <!-- Sales Chart -->
    <div class="card" style="margin-bottom: 0;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="trending-up" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
            <span>Grafik Tren Pendapatan Penjualan</span>
        </h3>
        <canvas id="salesChart" height="220"></canvas>
    </div>

    <!-- Low Stock Alert -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--savonia-navy); display: flex; align-items: center; gap: 8px;">
                <i data-lucide="alert-triangle" style="width: 18px; height: 18px; color: #F59E0B;"></i>
                <span>Peringatan Stok Menipis</span>
            </h3>
            <a href="{{ route('admin.products.index') }}" style="font-size: 0.78rem; color: var(--savonia-orange); font-weight: 700;">Kelola</a>
        </div>

        @if($lowStockProducts->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($lowStockProducts as $prod)
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="{{ $prod->image }}" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                            <div>
                                <h5 style="font-size: 0.85rem; font-weight: 700; color: var(--savonia-navy); line-height: 1.2;">
                                    {{ Str::limit($prod->name, 22) }}
                                </h5>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $prod->category->name }}</span>
                            </div>
                        </div>
                        <span style="background: #FEE2E2; color: #B91C1C; padding: 2px 8px; border-radius: 9999px; font-weight: 800; font-size: 0.75rem;">
                            Sisa {{ $prod->stock }} pcs
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 20px;">Semua stok produk dalam kondisi aman.</p>
        @endif
    </div>
</div>

<!-- RECENT ORDERS TABLE -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--savonia-navy);">
            Transaksi Pesanan Terbaru
        </h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">
            <span>Lihat Semua Pesanan</span>
            <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Kurir & Kota</th>
                    <th>Total Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                    <tr>
                        <td>
                            <strong style="color: var(--savonia-navy);">{{ $order->invoice_number }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>{{ $order->recipient_name }}</td>
                        <td>{{ $order->shipping_courier }} - {{ $order->shipping_city }}</td>
                        <td>
                            <strong style="color: var(--savonia-orange);">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            @php $badge = $order->status_badge; @endphp
                            <span class="badge" style="{{ $badge['class'] == 'bg-emerald-100 text-emerald-700 border-emerald-300' ? 'background: #ECFDF5; color: #047857;' : ($badge['class'] == 'bg-amber-100 text-amber-700 border-amber-300' ? 'background: #FFFBEB; color: #B45309;' : 'background: #EFF6FF; color: #1D4ED8;') }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline btn-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Omzet Penjualan (Rp)',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(255, 102, 0, 0.85)',
                borderColor: '#FF6600',
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
