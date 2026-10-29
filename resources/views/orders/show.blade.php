@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->invoice_number . ' - Savonia.id')

@section('content')
<div class="container section-padding">
    <!-- Header with Action -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; margin-bottom: 28px;">
        <div>
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 4px;">
                <a href="{{ route('home') }}">Beranda</a> / <a href="{{ route('orders.index') }}">Pesanan Saya</a> / {{ $order->invoice_number }}
            </div>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--savonia-navy);">
                Invoice: <span style="color: var(--savonia-orange);">{{ $order->invoice_number }}</span>
            </h1>
            <span style="font-size: 0.85rem; color: var(--text-secondary);">
                Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
            </span>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('orders.invoice', $order->invoice_number) }}" target="_blank" class="btn btn-outline btn-sm">
                <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
                <span>Cetak Nota / Invoice</span>
            </a>
            @if(!$order->isPaid())
                <form action="{{ route('orders.simulate', $order->invoice_number) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background: #10B981; color: white;" title="Khusus pengujian / sidang tugas akhir tanpa kartu asli">
                        ⚡ Simulasi Bayar Lunas (Demo TA)
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- STATUS TIMELINE PROGRESS BAR -->
    <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 30px; box-shadow: var(--shadow-sm); margin-bottom: 32px;">
        <h4 style="font-size: 1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 24px;">Status Pesanan Saat Ini:</h4>
        
        @php
            $statuses = ['pending' => 'Menunggu Pembayaran', 'paid' => 'Pembayaran Terverifikasi', 'processing' => 'Sedang Dikemas', 'shipped' => 'Dalam Pengiriman', 'completed' => 'Pesanan Selesai'];
            $currentIndex = match($order->status) {
                'pending' => 0,
                'paid' => 1,
                'processing' => 2,
                'shipped' => 3,
                'completed' => 4,
                'cancelled' => -1,
                default => 0,
            };
        @endphp

        @if($order->status == 'cancelled')
            <div style="background: #FEF2F2; color: #EF4444; padding: 14px 20px; border-radius: var(--radius-md); font-weight: 700; text-align: center;">
                ❌ Pesanan Ini Telah Dibatalkan
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); position: relative; text-align: center; gap: 10px;">
                @foreach(array_values($statuses) as $index => $label)
                    <div style="display: flex; flex-direction: column; align-items: center; position: relative;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; z-index: 2; margin-bottom: 8px; {{ $index <= $currentIndex ? 'background: var(--savonia-gradient); color: white; box-shadow: 0 4px 10px rgba(255,102,0,0.3);' : 'background: #E2E8F0; color: #94A3B8;' }}">
                            @if($index < $currentIndex)
                                <i data-lucide="check" style="width: 18px; height: 18px;"></i>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                        <span style="font-size: 0.8rem; font-weight: {{ $index <= $currentIndex ? '700' : '500' }}; color: {{ $index <= $currentIndex ? 'var(--savonia-navy)' : 'var(--text-muted)' }};">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($order->tracking_number)
            <div style="margin-top: 24px; padding: 14px 20px; background: #F8FAFC; border-radius: var(--radius-sm); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Ekspedisi & Nomor Resi Pengiriman:</span>
                    <div style="font-weight: 800; color: var(--savonia-navy); font-size: 1rem;">
                        {{ $order->shipping_courier }} - <span style="color: var(--savonia-orange);">{{ $order->tracking_number }}</span>
                    </div>
                </div>
                <span style="background: #E0E7FF; color: #4338CA; padding: 4px 12px; border-radius: var(--radius-full); font-weight: 700; font-size: 0.78rem;">
                    Resi Aktif
                </span>
            </div>
        @endif
    </div>

    <!-- ORDER DETAILS & MIDTRANS PAYMENT BOX -->
    <div style="display: grid; grid-template-columns: 1.3fr 0.85fr; gap: 32px; align-items: start;">
        <!-- Left: Items list -->
        <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 32px; box-shadow: var(--shadow-sm);">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                Rincian Barang yang Dipesan
            </h3>

            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <img src="{{ $item->product_image }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--savonia-navy); line-height: 1.3;">
                                    {{ $item->product_name }}
                                </h4>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">
                                    {{ $item->quantity }} pcs x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <span style="font-weight: 800; color: var(--savonia-navy); font-size: 0.95rem;">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Recipient & Courier -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background: #F8FAFC; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <div>
                    <h5 style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Penerima & Alamat</h5>
                    <strong style="color: var(--savonia-navy); font-size: 0.9rem;">{{ $order->recipient_name }} ({{ $order->recipient_phone }})</strong>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 4px; line-height: 1.4;">
                        {{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}
                    </p>
                </div>
                <div>
                    <h5 style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Pengiriman</h5>
                    <strong style="color: var(--savonia-navy); font-size: 0.9rem;">{{ $order->shipping_courier }}</strong>
                    @if($order->customer_notes)
                        <div style="margin-top: 8px; font-size: 0.8rem; color: var(--text-secondary); font-style: italic;">
                            "{{ $order->customer_notes }}"
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Payment Action Box -->
        <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 32px; box-shadow: var(--shadow-sm); position: sticky; top: 96px;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                Rincian Pembayaran
            </h3>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary);">
                    <span>Subtotal Barang</span>
                    <span style="font-weight: 700; color: var(--savonia-navy);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary);">
                    <span>Ongkos Kirim</span>
                    <span style="font-weight: 700; color: {{ $order->shipping_cost == 0 ? 'var(--success)' : 'var(--savonia-navy)' }};">
                        {{ $order->shipping_cost == 0 ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}
                    </span>
                </div>
                <div style="border-top: 1px dashed var(--border-color); padding-top: 14px; display: flex; justify-content: space-between; align-items: baseline;">
                    <span style="font-size: 1.05rem; font-weight: 800; color: var(--savonia-navy);">Total Tagihan</span>
                    <span style="font-size: 1.5rem; font-weight: 800; color: var(--savonia-orange);">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- PAYMENT BUTTON OR BADGE -->
            @if($order->isPaid())
                <div data-payment-status="paid" style="background: #ECFDF5; border: 1.5px solid #A7F3D0; color: #065F46; padding: 18px; border-radius: var(--radius-md); text-align: center; margin-bottom: 14px;">
                    <i data-lucide="check-circle" style="width: 36px; height: 36px; color: #10B981; margin: 0 auto 8px;"></i>
                    <h4 style="font-size: 1.1rem; font-weight: 800;">✅ Lunas Terbayar</h4>
                    <p style="font-size: 0.8rem; margin-top: 4px; color: #047857;">
                        Dibayar pada {{ $order->paid_at ? $order->paid_at->translatedFormat('d F Y, H:i') : now()->translatedFormat('d F Y') }} WIB
                    </p>
                </div>
            @else
                <div data-payment-status="unpaid" style="margin-bottom: 18px;">
                    <button id="pay-button" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; box-shadow: var(--shadow-glow);">
                        <i data-lucide="credit-card" style="width: 20px; height: 20px;"></i>
                        <span>💳 Bayar Sekarang (Midtrans)</span>
                    </button>
                </div>

                <div style="background: #FEF3C7; border: 1px solid #FCD34D; padding: 14px; border-radius: var(--radius-sm); font-size: 0.8rem; color: #92400E; margin-bottom: 12px;">
                    ⏳ <strong>Menunggu Pembayaran</strong>: Klik tombol di atas untuk membuka pop-up Midtrans Snap (QRIS, VA Bank, GoPay, dll).
                </div>

                <!-- Fallback Instant Simulation for Thesis/Demo -->
                <form action="{{ route('orders.simulate', $order->invoice_number) }}" method="POST" style="margin-bottom: 16px;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: center; font-size: 0.825rem; background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D;">
                        <i data-lucide="zap" style="width: 15px; height: 15px; color: #D97706;"></i>
                        <span>Simulasi Bayar Lunas Langsung (Demo TA)</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('orders.track', ['invoice' => $order->invoice_number]) }}" class="btn btn-outline" style="width: 100%; justify-content: center; font-size: 0.85rem;">
                <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>
                <span>Cek Lacak Pengiriman</span>
            </a>
        </div>
    </div>
</div>

@if(!$order->isPaid() && $order->snap_token)
<script type="text/javascript">
    // Auto-polling untuk check payment status dari API
    let pollingInterval = null;
    let pollingAttempts = 0;
    const MAX_POLLING_ATTEMPTS = 300; // 10 menit (300 * 2 detik)

    function checkPaymentStatus() {
        pollingAttempts++;
        
        fetch('{{ route('orders.check-payment', $order->invoice_number) }}')
            .then(response => response.json())
            .then(data => {
                console.log('Payment check:', data);
                
                // Jika status berubah ke PAID
                if (data.status === 'paid') {
                    showNotification('✅ ' + data.message, 'success');
                    clearInterval(pollingInterval);
                    
                    // Reload halaman setelah 1.5 detik
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(err => {
                console.error('Payment status check error:', err);
            });

        // Stop polling setelah MAX_POLLING_ATTEMPTS
        if (pollingAttempts >= MAX_POLLING_ATTEMPTS) {
            clearInterval(pollingInterval);
            console.log('Polling stopped after 10 minutes');
        }
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? '#10B981' : '#3B82F6';
        const textColor = type === 'error' ? '#EF4444' : type === 'success' ? '#10B981' : '#3B82F6';
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3500);
    }

    // Start polling ketika page load
    document.addEventListener('DOMContentLoaded', function() {
        if ('{{ $order->payment_status }}' === 'unpaid') {
            showNotification('🔄 Monitoring status pembayaran... (setiap 2 detik)', 'info');
            pollingInterval = setInterval(checkPaymentStatus, 2000);
            
            // Check sekali immediately
            checkPaymentStatus();
        }
    });

    // Cleanup polling ketika user meninggalkan halaman
    window.addEventListener('beforeunload', function() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });

    document.getElementById('pay-button').onclick = function () {
        const snapToken = '{{ $order->snap_token }}';
        
        if (snapToken.startsWith('mock-snap-')) {
            if (confirm("Pemberitahuan Demo:\nKunci Midtrans di .env masih menggunakan placeholder (SB-Mid-server-TEST-KEY).\n\nApakah Anda ingin langsung menyimulasikan pembayaran lunas untuk demo tugas akhir sekarang?")) {
                document.querySelector('form[action="{{ route('orders.simulate', $order->invoice_number) }}"]').submit();
            }
            return;
        }

        // Trigger Snap Popup Real Sandbox
        snap.pay(snapToken, {
            onSuccess: function(result){
                showNotification('✅ Pembayaran berhasil! Memverifikasi status...', 'success');
                setTimeout(() => location.reload(), 2000);
            },
            onPending: function(result){
                showNotification('⏳ Menunggu pembayaran diselesaikan...', 'info');
                setTimeout(() => location.reload(), 2000);
            },
            onError: function(result){
                showNotification('❌ Terjadi kesalahan pada transaksi Midtrans', 'error');
            },
            onClose: function(){
                showNotification('🔄 Memeriksa status pembayaran...', 'info');
                setTimeout(() => location.reload(), 1500);
            }
        });
    };
</script>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
</style>
@endif
@endsection
