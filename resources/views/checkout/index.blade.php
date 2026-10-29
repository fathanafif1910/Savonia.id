@extends('layouts.app')

@section('title', 'Checkout Pengiriman - Savonia.id')

@section('content')
<div class="container section-padding">
    <!-- Breadcrumb -->
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('home') }}" style="color: var(--text-secondary);">Beranda</a>
        <span>/</span>
        <a href="{{ route('cart.index') }}" style="color: var(--text-secondary);">Keranjang</a>
        <span>/</span>
        <span style="color: var(--savonia-orange); font-weight: 600;">Checkout Pengiriman</span>
    </div>

    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--savonia-navy); letter-spacing: -0.5px;">
            Informasi Pengiriman & Pembayaran
        </h1>
        <p style="color: var(--text-secondary); font-size: 0.95rem;">
            Lengkapi alamat pengiriman untuk menghitung ongkir dan melanjutkan pembayaran Midtrans.
        </p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1.25fr 0.85fr; gap: 40px; align-items: start;">
            <!-- LEFT: Shipping Form -->
            <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 36px; box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 14px;">
                    <i data-lucide="map-pin" style="width: 20px; height: 20px; color: var(--savonia-orange);"></i>
                    <span>Alamat Pengiriman Penerima</span>
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                            Nama Penerima <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', $user->name) }}" required
                            style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                            Nomor WhatsApp / HP <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="tel" name="recipient_phone" value="{{ old('recipient_phone', $user->phone ?? '08') }}" required
                            style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Alamat Lengkap <span style="color: var(--danger);">*</span>
                    </label>
                    <textarea name="shipping_address" rows="3" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan..."
                        style="width: 100%; padding: 12px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">{{ old('shipping_address', $user->address) }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                            Kota / Kabupaten <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" id="shipping_city" name="shipping_city" value="{{ old('shipping_city', $user->city ?? 'Banda Aceh') }}" required
                            placeholder="Contoh: Banda Aceh / Aceh Besar / Medan"
                            oninput="handleCityChange()"
                            style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                        <div id="city-status-badge" style="margin-top: 6px; font-size: 0.78rem;"></div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                            Kode Pos <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code', $user->postal_code ?? '23233') }}" required
                            style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                    </div>
                </div>

                <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 18px; display: flex; align-items: center; gap: 10px; border-top: 1px solid var(--border-color); padding-top: 24px;">
                    <i data-lucide="truck" style="width: 20px; height: 20px; color: var(--savonia-orange);"></i>
                    <span>Pilihan Ekspedisi Kurir</span>
                </h3>

                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                    <!-- 1. GRAB EXPRESS (KHUSUS BANDA ACEH - GRATIS) -->
                    <label id="grab-option" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border: 1.5px solid var(--border-color); border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition);" class="courier-option">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="radio" name="shipping_courier" id="courier-grab" value="Grab Express (Kawasan Banda Aceh)" data-cost="0" checked onchange="updateShipping(0)">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <strong style="color: var(--savonia-navy); font-size: 0.925rem;">Grab Express (Kawasan Banda Aceh)</strong>
                                    <span style="background: rgba(16, 185, 129, 0.12); color: #059669; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 99px;">GRATIS ONGKIR</span>
                                </div>
                                <span id="grab-desc" style="display: block; font-size: 0.78rem; color: var(--text-muted);">Pengiriman Instan Langsung ke Alamat (Khusus Kota Banda Aceh)</span>
                            </div>
                        </div>
                        <span style="font-weight: 700; color: var(--success);">GRATIS (Rp 0)</span>
                    </label>

                    <!-- 2. J&T EXPRESS (LUAR DAERAH - SISTEM DAERAH) -->
                    <label id="jnt-option" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border: 1.5px solid var(--border-color); border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition);" class="courier-option">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="radio" name="shipping_courier" id="courier-jnt" value="J&T Express (Reguler)" data-cost="22000" onchange="updateShipping(parseInt(this.getAttribute('data-cost')))">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <strong style="color: var(--savonia-navy); font-size: 0.925rem;">J&T Express (Reguler - Luar Daerah)</strong>
                                    <span id="jnt-zone-badge" style="background: rgba(255, 102, 0, 0.1); color: var(--savonia-orange); font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 99px;">SISTEM DAERAH</span>
                                </div>
                                <span id="jnt-desc" style="display: block; font-size: 0.78rem; color: var(--text-muted);">Estimasi 2-4 Hari Kerja (Tarif Sesuai Kota/Kabupaten Tujuan)</span>
                            </div>
                        </div>
                        <span id="jnt-cost-display" style="font-weight: 700; color: var(--savonia-orange);">Rp 22.000</span>
                    </label>

                    <!-- 3. AMBIL SENDIRI DI TOKO -->
                    <label id="pickup-option" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border: 1.5px solid var(--border-color); border-radius: var(--radius-sm); cursor: pointer; transition: var(--transition);" class="courier-option">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="radio" name="shipping_courier" id="courier-pickup" value="Ambil di Toko (Self Pick-up)" data-cost="0" onchange="updateShipping(0)">
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <strong style="color: var(--savonia-navy); font-size: 0.925rem;">Ambil Sendiri di Toko (Savonia.id)</strong>
                                    <span style="background: rgba(59, 130, 246, 0.1); color: #2563eb; font-size: 0.72rem; font-weight: 700; padding: 2px 8px; border-radius: 99px;">PICK-UP</span>
                                </div>
                                <span style="display: block; font-size: 0.78rem; color: var(--text-muted);">Punge Ujong, Kec.Meuraxa, Kota Banda Aceh, Aceh (Tanpa Ongkir)</span>
                            </div>
                        </div>
                        <span style="font-weight: 700; color: var(--success);">GRATIS (Rp 0)</span>
                    </label>
                </div>

                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Catatan Pesanan (Opsional)
                    </label>
                    <input type="text" name="customer_notes" placeholder="Contoh: Titip di satpam / Bubble wrap tebal ya..."
                        style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                </div>
            </div>

            <!-- RIGHT: Order Summary -->
            <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 32px; box-shadow: var(--shadow-sm); position: sticky; top: 96px;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    Pesanan Anda ({{ $cartItems->sum('quantity') }} Item)
                </h3>

                <!-- Items Preview -->
                <div style="max-height: 240px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px; padding-right: 6px;">
                    @foreach($cartItems as $cart)
                        @if($cart->product)
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="{{ $cart->product->image }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-color);">
                                    <div>
                                        <h5 style="font-size: 0.85rem; font-weight: 700; color: var(--savonia-navy); line-height: 1.3;">
                                            {{ Str::limit($cart->product->name, 28) }}
                                        </h5>
                                        <span style="font-size: 0.75rem; color: var(--text-muted);">
                                            {{ $cart->quantity }} x Rp {{ number_format($cart->product->final_price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <span style="font-weight: 700; font-size: 0.9rem; color: var(--savonia-navy);">
                                    Rp {{ number_format($cart->subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Calculation breakdown -->
                <div style="border-top: 1px solid var(--border-color); padding-top: 18px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary);">
                        <span>Subtotal Produk</span>
                        <span style="font-weight: 700; color: var(--savonia-navy);">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-secondary);">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-display" style="font-weight: 700; color: var(--success);">GRATIS (Rp 0)</span>
                    </div>

                    <div style="border-top: 1px dashed var(--border-color); padding-top: 14px; display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-size: 1.05rem; font-weight: 800; color: var(--savonia-navy);">Total Pembayaran</span>
                        <span id="grand-total-display" style="font-size: 1.5rem; font-weight: 800; color: var(--savonia-orange);">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; box-shadow: var(--shadow-glow);">
                    <i data-lucide="lock" style="width: 18px; height: 18px;"></i>
                    <span>Bayar Sekarang (Midtrans)</span>
                </button>

                <div style="margin-top: 18px; text-align: center; font-size: 0.78rem; color: var(--text-muted);">
                    ⚡ Pop-up pembayaran instan Midtrans Snap akan muncul setelah klik tombol di atas.
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const subtotal = {{ (float) $subtotal }};

    function calculateJntCost(cityName) {
        const city = (cityName || '').toLowerCase();
        if (city.includes('aceh besar') || city.includes('sabang')) {
            return { cost: 15000, label: 'Aceh Besar & Sabang' };
        } else if (city.includes('aceh') || city.includes('lhokseumawe') || city.includes('langsa') || city.includes('bireuen') || city.includes('pidie') || city.includes('meulaboh') || city.includes('takengon') || city.includes('subulussalam') || city.includes('singkil')) {
            return { cost: 18000, label: 'Provinsi Aceh (Luar Banda Aceh)' };
        } else if (city.includes('medan') || city.includes('sumut') || city.includes('sumatera utara') || city.includes('deli')) {
            return { cost: 25000, label: 'Sumatera Utara / Medan' };
        } else if (city.includes('jakarta') || city.includes('jawa') || city.includes('bandung') || city.includes('surabaya') || city.includes('semarang') || city.includes('tangerang') || city.includes('bekasi') || city.includes('bogor')) {
            return { cost: 35000, label: 'Jabodetabek & P. Jawa' };
        } else {
            return { cost: 22000, label: 'Luar Daerah (Reguler)' };
        }
    }

    function handleCityChange() {
        const cityInput = document.getElementById('shipping_city');
        const cityVal = cityInput ? cityInput.value.trim() : '';
        const cityLower = cityVal.toLowerCase();
        const isBandaAceh = cityLower.includes('banda aceh') || cityVal === '';

        const grabRadio = document.getElementById('courier-grab');
        const jntRadio = document.getElementById('courier-jnt');
        const pickupRadio = document.getElementById('courier-pickup');
        const grabOption = document.getElementById('grab-option');
        const statusBadge = document.getElementById('city-status-badge');

        const jntData = calculateJntCost(cityVal);
        jntRadio.setAttribute('data-cost', jntData.cost);
        document.getElementById('jnt-cost-display').innerText = 'Rp ' + jntData.cost.toLocaleString('id-ID');
        document.getElementById('jnt-zone-badge').innerText = jntData.label;

        if (isBandaAceh) {
            grabOption.style.opacity = '1';
            grabOption.style.pointerEvents = 'auto';
            if (statusBadge) {
                statusBadge.innerHTML = '<span style="color: #059669; font-weight: 700;">✓ Kawasan Banda Aceh:</span> Gratis Ongkir dengan Grab Express / Ambil di Toko.';
            }

            if (!pickupRadio.checked) {
                grabRadio.checked = true;
                updateShipping(0);
            }
        } else {
            grabOption.style.opacity = '0.45';
            grabOption.style.pointerEvents = 'none';
            if (statusBadge) {
                statusBadge.innerHTML = '<span style="color: var(--savonia-orange); font-weight: 700;">🚚 Luar Banda Aceh:</span> Pengiriman via J&T Express sistem daerah (' + jntData.label + ').';
            }

            if (!pickupRadio.checked) {
                jntRadio.checked = true;
                updateShipping(jntData.cost);
            }
        }
    }

    function updateShipping(cost) {
        const grandTotal = subtotal + cost;
        const displayEl = document.getElementById('shipping-display');
        if (cost === 0) {
            displayEl.innerText = 'GRATIS (Rp 0)';
            displayEl.style.color = 'var(--success)';
        } else {
            displayEl.innerText = 'Rp ' + cost.toLocaleString('id-ID');
            displayEl.style.color = 'var(--savonia-navy)';
        }
        document.getElementById('grand-total-display').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        handleCityChange();
    });
</script>
@endsection
