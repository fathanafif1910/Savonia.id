@extends('layouts.admin')

@section('title', 'Kelola Pesanan')

@section('content')
<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
    <div>
        <h1 style="font-size: 1.6rem; font-weight: 800; color: var(--text-dark);">Kelola Pesanan</h1>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 4px;">Manajemen seluruh pesanan pelanggan Savonia.id</p>
    </div>
</div>

{{-- Filter & Search --}}
<div class="card" style="padding: 16px 20px; margin-bottom: 20px;">
    <form action="{{ route('admin.orders.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari invoice, nama, resi..."
               style="flex: 1; min-width: 200px; padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.85rem; font-family: inherit;">
        
        <select name="status" style="padding: 8px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.85rem; font-family: inherit; min-width: 150px;">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>✅ Dibayar</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>📦 Diproses</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>🚚 Dikirim</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>✔️ Selesai</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm">
            <i data-lucide="search" style="width: 14px; height: 14px;"></i> Filter
        </button>
        @if(request('q') || request('status'))
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">Reset</a>
        @endif
    </form>
</div>

{{-- Orders Table --}}
<div class="card" style="padding: 0; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Tanggal</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>
                    <div style="font-weight: 700; color: var(--savonia-orange); font-size: 0.82rem;">{{ $order->invoice_number }}</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->items->count() }} item</div>
                </td>
                <td>
                    <div style="font-weight: 600;">{{ $order->user->name ?? '-' }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->recipient_name }}</div>
                </td>
                <td style="font-weight: 700;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                <td>
                    @php
                        $statusColors = [
                            'pending' => ['bg' => '#FFF7ED', 'text' => '#C2410C', 'label' => '⏳ Pending'],
                            'paid' => ['bg' => '#ECFDF5', 'text' => '#047857', 'label' => '✅ Dibayar'],
                            'processing' => ['bg' => '#EFF6FF', 'text' => '#1D4ED8', 'label' => '📦 Diproses'],
                            'shipped' => ['bg' => '#F5F3FF', 'text' => '#6D28D9', 'label' => '🚚 Dikirim'],
                            'completed' => ['bg' => '#F0FDF4', 'text' => '#15803D', 'label' => '✔️ Selesai'],
                            'cancelled' => ['bg' => '#FEF2F2', 'text' => '#DC2626', 'label' => '❌ Batal'],
                        ];
                        $s = $statusColors[$order->status] ?? $statusColors['pending'];
                    @endphp
                    <span class="badge" style="background: {{ $s['bg'] }}; color: {{ $s['text'] }};">{{ $s['label'] }}</span>
                </td>
                <td>
                    @php
                        $payColors = [
                            'unpaid' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Belum Bayar'],
                            'paid' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'label' => 'Lunas'],
                            'expired' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'label' => 'Expired'],
                            'failed' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'label' => 'Gagal'],
                            'refunded' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'label' => 'Refund'],
                        ];
                        $p = $payColors[$order->payment_status] ?? $payColors['unpaid'];
                    @endphp
                    <span class="badge" style="background: {{ $p['bg'] }}; color: {{ $p['text'] }};">{{ $p['label'] }}</span>
                </td>
                <td style="font-size: 0.82rem; color: var(--text-muted);">
                    {{ $order->created_at->format('d M Y') }}<br>
                    <span style="font-size: 0.72rem;">{{ $order->created_at->format('H:i') }} WIB</span>
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline btn-sm">
                        <i data-lucide="eye" style="width: 14px; height: 14px;"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                    <i data-lucide="inbox" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.4;"></i>
                    <div style="font-weight: 700; margin-bottom: 4px;">Belum Ada Pesanan</div>
                    <div style="font-size: 0.82rem;">Pesanan dari pelanggan akan muncul di sini.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($orders->hasPages())
<div style="margin-top: 20px; display: flex; justify-content: center;">
    {{ $orders->links() }}
</div>
@endif
@endsection
