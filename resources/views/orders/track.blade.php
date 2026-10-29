@extends('layouts.app')

@section('title', 'Lacak Pesanan Aksesoris - Savonia.id')

@section('content')
<div class="container section-padding">
    <div style="max-width: 680px; margin: 0 auto;">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 36px;">
            <div class="section-subtitle">PELACAKAN REALTIME</div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 10px;">
                Lacak Status Pesananmu
            </h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">
                Masukkan nomor invoice pesananmu (contoh: <code>INV-SAV-20261020-001</code>) untuk mengetahui status pengemasan dan posisi paket terkini.
            </p>
        </div>

        <!-- Search Box -->
        <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 32px; box-shadow: var(--shadow-sm); margin-bottom: 36px;">
            <form action="{{ route('orders.track') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap;">
                <input type="text" name="invoice" value="{{ request('invoice') }}" required placeholder="Masukkan nomor invoice (contoh: INV-SAV-...)"
                    style="flex: 1; min-width: 260px; padding: 12px 18px; border-radius: var(--radius-full); border: 1.5px solid var(--border-color); font-size: 0.95rem;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">
                    <i data-lucide="search" style="width: 18px; height: 18px;"></i>
                    <span>Cari Pesanan</span>
                </button>
            </form>
        </div>

        <!-- Result Box -->
        @if(request('invoice'))
            @if($order)
                <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 32px; box-shadow: var(--shadow-sm);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px;">
                        <div>
                            <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Nomor Invoice</span>
                            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy);">{{ $order->invoice_number }}</h3>
                        </div>
                        <div>
                            @php $badge = $order->status_badge; @endphp
                            <span style="font-size: 0.85rem; font-weight: 700; padding: 6px 14px; border-radius: var(--radius-full); border: 1px solid; {{ $badge['class'] == 'bg-emerald-100 text-emerald-700 border-emerald-300' ? 'background: #ECFDF5; color: #047857; border-color: #A7F3D0;' : ($badge['class'] == 'bg-amber-100 text-amber-700 border-amber-300' ? 'background: #FFFBEB; color: #B45309; border-color: #FDE68A;' : 'background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE;') }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; font-size: 0.9rem;">
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">Penerima:</span>
                            <strong style="color: var(--savonia-navy);">{{ $order->recipient_name }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">Tujuan:</span>
                            <strong style="color: var(--savonia-navy);">{{ $order->shipping_city }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">Ekspedisi:</span>
                            <strong style="color: var(--savonia-navy);">{{ $order->shipping_courier }}</strong>
                        </div>
                        <div>
                            <span style="color: var(--text-muted); font-size: 0.8rem; display: block;">Nomor Resi:</span>
                            <strong style="color: var(--savonia-orange);">{{ $order->tracking_number ?? 'Belum terbit (Menunggu kurir)' }}</strong>
                        </div>
                    </div>

                    <a href="{{ route('orders.show', $order->invoice_number) }}" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <span>Lihat Rincian Lengkap & Pembayaran</span>
                        <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
                    </a>
                </div>
            @else
                <div style="text-align: center; padding: 40px; background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                    <i data-lucide="alert-circle" style="width: 40px; height: 40px; color: #EF4444; margin-bottom: 10px;"></i>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Pesanan Tidak Ditemukan
                    </h3>
                    <p style="font-size: 0.875rem; color: var(--text-secondary);">
                        Nomor invoice <strong>"{{ request('invoice') }}"</strong> tidak terdaftar di sistem Savonia.id. Pastikan Anda menyalin nomor invoice dengan benar.
                    </p>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
