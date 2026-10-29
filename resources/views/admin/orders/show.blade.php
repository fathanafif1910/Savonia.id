@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 28px;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-sm">
        <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i> Kembali
    </a>
    <div>
        <h1 style="font-size: 1.4rem; font-weight: 800; color: var(--text-dark);">Detail Pesanan</h1>
        <p style="color: var(--savonia-orange); font-size: 0.85rem; font-weight: 700;">{{ $order->invoice_number }}</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: 24px;">
    {{-- Left Column --}}
    <div>
        {{-- Order Items --}}
        <div class="card">
            <h3 style="font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="package" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
                Item Pesanan ({{ $order->items->count() }} produk)
            </h3>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Harga</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $item->product_image ?: 'https://via.placeholder.com/50' }}" alt="{{ $item->product_name }}"
                                     style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                                <div>
                                    <div style="font-weight: 600; font-size: 0.85rem;">{{ $item->product_name }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">SKU: {{ $item->product_sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: center; font-weight: 600;">{{ $item->quantity }}</td>
                        <td style="text-align: right; font-size: 0.85rem;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="border-top: 2px solid var(--border); padding-top: 16px; margin-top: 8px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.875rem;">
                    <span style="color: var(--text-muted);">Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.875rem;">
                    <span style="color: var(--text-muted);">Ongkos Kirim ({{ $order->shipping_courier }})</span>
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 800; color: var(--savonia-orange);">
                    <span>GRAND TOTAL</span>
                    <span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="card">
            <h3 style="font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="truck" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
                Informasi Pengiriman
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Nama Penerima</label>
                    <p style="font-weight: 600; margin-top: 4px;">{{ $order->recipient_name }}</p>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Telepon</label>
                    <p style="font-weight: 600; margin-top: 4px;">{{ $order->recipient_phone }}</p>
                </div>
                <div style="grid-column: span 2;">
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Alamat Lengkap</label>
                    <p style="font-weight: 500; margin-top: 4px;">{{ $order->shipping_address }}, {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Kurir</label>
                    <p style="font-weight: 600; margin-top: 4px;">{{ $order->shipping_courier }}</p>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">No. Resi</label>
                    <p style="font-weight: 600; margin-top: 4px; color: {{ $order->tracking_number ? '#047857' : '#94A3B8' }};">{{ $order->tracking_number ?: 'Belum ada' }}</p>
                </div>
            </div>
            @if($order->customer_notes)
            <div style="margin-top: 16px; padding: 12px 16px; background: #FFF7ED; border-radius: 8px; border: 1px solid #FED7AA;">
                <label style="font-size: 0.72rem; font-weight: 700; color: #C2410C;">CATATAN PELANGGAN</label>
                <p style="margin-top: 4px; font-size: 0.85rem;">{{ $order->customer_notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Right Column — Update Status --}}
    <div>
        {{-- Status Card --}}
        <div class="card" style="border: 2px solid var(--savonia-orange);">
            <h3 style="font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="settings" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
                Update Status Pesanan
            </h3>

            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px; color: var(--text-muted);">Status Pesanan</label>
                    <select name="status" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.875rem;">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>✅ Dibayar</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>📦 Sedang Diproses</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>🚚 Dikirim</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>✔️ Selesai</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px; color: var(--text-muted);">Status Pembayaran</label>
                    <select name="payment_status" style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.875rem;">
                        <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>💳 Belum Bayar</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>✅ Lunas</option>
                        <option value="expired" {{ $order->payment_status == 'expired' ? 'selected' : '' }}>⏰ Expired</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>❌ Gagal</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>🔄 Refund</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px; color: var(--text-muted);">Nomor Resi Pengiriman</label>
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Masukkan nomor resi..."
                           style="width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 0.875rem;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;">
                    <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- Order Timeline --}}
        <div class="card">
            <h3 style="font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="clock" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
                Timeline Pesanan
            </h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; margin-top: 4px; flex-shrink: 0;"></div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.85rem;">Pesanan Dibuat</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
                @if($order->paid_at)
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; margin-top: 4px; flex-shrink: 0;"></div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.85rem;">Pembayaran Diterima</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($order->paid_at)->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
                @endif
                @if($order->shipped_at)
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; margin-top: 4px; flex-shrink: 0;"></div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.85rem;">Pesanan Dikirim</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($order->shipped_at)->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
                @endif
                @if($order->completed_at)
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; margin-top: 4px; flex-shrink: 0;"></div>
                    <div>
                        <div style="font-weight: 600; font-size: 0.85rem;">Pesanan Selesai</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ \Carbon\Carbon::parse($order->completed_at)->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="card">
            <h3 style="font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="user" style="width: 18px; height: 18px; color: var(--savonia-orange);"></i>
                Info Pelanggan
            </h3>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem;">
                    {{ strtoupper(substr($order->user->name ?? 'C', 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight: 700;">{{ $order->user->name ?? '-' }}</div>
                    <div style="font-size: 0.82rem; color: var(--text-muted);">{{ $order->user->email ?? '-' }}</div>
                    <div style="font-size: 0.82rem; color: var(--text-muted);">{{ $order->user->phone ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
