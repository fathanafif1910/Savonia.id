@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-dark);">Laporan Penjualan</h1>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Laporan transaksi penjualan Savonia.id untuk Tugas Akhir</p>
    </div>
    <a href="{{ route('admin.reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-primary">
        <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
        Cetak Laporan (PDF)
    </a>
</div>

{{-- Date Filter --}}
<div class="card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted);">Dari:</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                   style="padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.85rem; font-family: inherit;">
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 0.82rem; font-weight: 700; color: var(--text-muted);">Sampai:</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                   style="padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.85rem; font-family: inherit;">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">
            <i data-lucide="filter" style="width: 14px; height: 14px;"></i> Tampilkan
        </button>
    </form>
</div>

{{-- Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Pesanan</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--text-dark); margin-top: 8px;">{{ $totalOrders }}</div>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Pesanan Lunas</div>
        <div style="font-size: 2rem; font-weight: 800; color: #10B981; margin-top: 8px;">{{ $paidOrdersCount }}</div>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Item Terjual</div>
        <div style="font-size: 2rem; font-weight: 800; color: #3B82F6; margin-top: 8px;">{{ $totalItemsSold }}</div>
    </div>
    <div class="card" style="text-align: center; margin-bottom: 0;">
        <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Pendapatan</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: var(--savonia-orange); margin-top: 8px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
</div>

{{-- Report Table --}}
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
        <h3 style="font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="file-text" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
            Data Transaksi Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        </h3>
        <span style="font-size: 0.82rem; color: var(--text-muted);">{{ $orders->count() }} transaksi</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th style="text-align: right;">Grand Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td style="font-weight: 600;">{{ $index + 1 }}</td>
                <td style="font-weight: 700; color: var(--savonia-orange); font-size: 0.82rem;">{{ $order->invoice_number }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $order->user->name ?? '-' }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->recipient_name }}</div>
                </td>
                <td>
                    @foreach($order->items->take(2) as $item)
                        <div style="font-size: 0.82rem;">{{ $item->product_name }} ({{ $item->quantity }}x)</div>
                    @endforeach
                    @if($order->items->count() > 2)
                        <span style="font-size: 0.72rem; color: var(--text-muted);">+{{ $order->items->count() - 2 }} item lainnya</span>
                    @endif
                </td>
                <td style="text-align: right; font-weight: 700;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                <td>
                    @php
                        $statusColors = [
                            'pending' => '#C2410C', 'paid' => '#047857', 'processing' => '#1D4ED8',
                            'shipped' => '#6D28D9', 'completed' => '#15803D', 'cancelled' => '#DC2626',
                        ];
                    @endphp
                    <span style="font-size: 0.8rem; font-weight: 700; color: {{ $statusColors[$order->status] ?? '#64748B' }};">{{ ucfirst($order->status) }}</span>
                </td>
                <td>
                    <span class="badge" style="background: {{ $order->payment_status == 'paid' ? '#D1FAE5' : '#FEF3C7' }}; color: {{ $order->payment_status == 'paid' ? '#065F46' : '#92400E' }};">
                        {{ $order->payment_status == 'paid' ? 'Lunas' : ucfirst($order->payment_status) }}
                    </span>
                </td>
                <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                    <i data-lucide="file-x" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.4;"></i>
                    <div style="font-weight: 700;">Tidak ada data transaksi pada periode ini.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
