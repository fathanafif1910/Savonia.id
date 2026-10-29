@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya - Savonia.id')

@section('content')
<div class="container section-padding">
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--savonia-navy); letter-spacing: -0.5px;">
            Riwayat Pesanan Saya
        </h1>
        <p style="color: var(--text-secondary); font-size: 0.95rem;">
            Pantau status transaksi, rincian pembayaran Midtrans, dan nomor resi pengiriman aksesorismu.
        </p>
    </div>

    @if($orders->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($orders as $order)
                <div style="background: white; border-radius: var(--radius-md); border: 1px solid var(--border-color); padding: 24px; box-shadow: var(--shadow-sm); transition: var(--transition);">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 18px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <span style="font-weight: 800; font-size: 1.05rem; color: var(--savonia-navy);">
                                {{ $order->invoice_number }}
                            </span>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">
                                {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        
                        <div>
                            @php $badge = $order->status_badge; @endphp
                            <span style="font-size: 0.78rem; font-weight: 700; padding: 4px 12px; border-radius: var(--radius-full); border: 1px solid; {{ $badge['class'] == 'bg-emerald-100 text-emerald-700 border-emerald-300' ? 'background: #ECFDF5; color: #047857; border-color: #A7F3D0;' : ($badge['class'] == 'bg-amber-100 text-amber-700 border-amber-300' ? 'background: #FFFBEB; color: #B45309; border-color: #FDE68A;' : 'background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE;') }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            @if($order->items->first())
                                <img src="{{ $order->items->first()->product_image }}" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                                <div>
                                    <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--savonia-navy);">
                                        {{ $order->items->first()->product_name }}
                                    </h4>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);">
                                        {{ $order->items->first()->quantity }} barang @if($order->items->count() > 1) + {{ $order->items->count() - 1 }} produk lainnya @endif
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div style="display: flex; align-items: center; gap: 24px;">
                            <div style="text-align: right;">
                                <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Total Tagihan</span>
                                <span style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-orange);">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </span>
                            </div>

                            <a href="{{ route('orders.show', $order->invoice_number) }}" class="btn btn-outline btn-sm">
                                <span>Detail Pesanan</span>
                                <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 32px; display: flex; justify-content: center;">
            {{ $orders->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 70px 20px; background: white; border-radius: var(--radius-lg); border: 1.5px dashed var(--border-color); max-width: 500px; margin: 0 auto;">
            <i data-lucide="receipt" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 14px;"></i>
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 6px;">Belum Ada Riwayat Pesanan</h3>
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px;">Kamu belum pernah melakukan transaksi di Savonia.id.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm">Jelajahi Produk</a>
        </div>
    @endif
</div>
@endsection
