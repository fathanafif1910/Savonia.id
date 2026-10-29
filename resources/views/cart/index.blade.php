@extends('layouts.app')

@section('title', 'Keranjang Belanja - Savonia.id')

@section('content')
<div class="container section-padding">
    <div style="margin-bottom: 28px;">
        <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--savonia-navy); letter-spacing: -0.5px;">
            Keranjang Belanja Kamu
        </h1>
        <p style="color: var(--text-secondary); font-size: 0.95rem;">
            Periksa kembali aksesoris teknologi pilihanmu sebelum melanjutkan ke proses checkout.
        </p>
    </div>

    @if($cartItems->count() > 0)
        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 36px; align-items: start;">
            <!-- Cart Items List -->
            <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); overflow: hidden; box-shadow: var(--shadow-sm);">
                <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-color); background: #F8FAFC; display: grid; grid-template-columns: 3fr 1.5fr 1.5fr 1.5fr 0.5fr; font-size: 0.85rem; font-weight: 700; color: var(--savonia-navy);">
                    <span>PRODUK</span>
                    <span style="text-align: center;">HARGA SATUAN</span>
                    <span style="text-align: center;">JUMLAH</span>
                    <span style="text-align: right;">SUBTOTAL</span>
                    <span></span>
                </div>

                <div style="divide-y: 1px solid var(--border-color);">
                    @foreach($cartItems as $item)
                        @if($item->product)
                            <div style="padding: 20px 24px; display: grid; grid-template-columns: 3fr 1.5fr 1.5fr 1.5fr 0.5fr; align-items: center; border-bottom: 1px solid var(--border-color);">
                                <!-- Product Info -->
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                                    <div>
                                        <a href="{{ route('catalog.show', $item->product->slug) }}" style="font-weight: 700; color: var(--savonia-navy); font-size: 0.95rem; line-height: 1.3; display: block; margin-bottom: 4px;">
                                            {{ $item->product->name }}
                                        </a>
                                        <span style="font-size: 0.78rem; color: var(--text-muted);">
                                            {{ $item->product->category->name }} &bull; {{ $item->product->material ?? 'Original' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Unit Price -->
                                <div style="text-align: center;">
                                    <span style="font-weight: 700; color: var(--savonia-navy); font-size: 0.95rem;">
                                        Rp {{ number_format($item->product->final_price, 0, ',', '.') }}
                                    </span>
                                    @if($item->product->has_discount)
                                        <span style="display: block; font-size: 0.75rem; color: var(--text-muted); text-decoration: line-through;">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Quantity Form -->
                                <div style="display: flex; justify-content: center;">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: var(--radius-full); overflow: hidden; background: #F8FAFC;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}" style="padding: 4px 10px; background: none; border: none; cursor: pointer; font-weight: 700; font-size: 0.9rem;">-</button>
                                        <span style="padding: 4px 8px; font-weight: 700; font-size: 0.9rem; min-width: 28px; text-align: center;">{{ $item->quantity }}</span>
                                        <button type="submit" name="quantity" value="{{ min($item->product->stock, $item->quantity + 1) }}" style="padding: 4px 10px; background: none; border: none; cursor: pointer; font-weight: 700; font-size: 0.9rem;">+</button>
                                    </form>
                                </div>

                                <!-- Subtotal -->
                                <div style="text-align: right; font-weight: 800; color: var(--savonia-orange); font-size: 1rem;">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </div>

                                <!-- Remove -->
                                <div style="text-align: right;">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 4px; transition: color 0.2s;" title="Hapus Item">
                                            <i data-lucide="trash-2" style="width: 18px; height: 18px; color: #EF4444;"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div style="padding: 16px 24px; background: #FAFBFD; display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('catalog.index') }}" style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; font-weight: 600; color: var(--savonia-orange);">
                        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
                        <span>Lanjut Pilih Aksesoris Lain</span>
                    </a>
                    <span style="font-size: 0.85rem; color: var(--text-secondary);">
                        Total Berat Paket: <strong>{{ $totalWeight }} gram</strong>
                    </span>
                </div>
            </div>

            <!-- Summary Box -->
            <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 28px; box-shadow: var(--shadow-sm); position: sticky; top: 96px;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    Ringkasan Belanja
                </h3>

                <div style="display: flex; flex-direction: column; gap: 14px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem; color: var(--text-secondary);">
                        <span>Total Harga ({{ $cartItems->sum('quantity') }} barang)</span>
                        <span style="font-weight: 700; color: var(--savonia-navy);">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem; color: var(--text-secondary);">
                        <span>Ongkos Kirim</span>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Dihitung di checkout</span>
                    </div>

                    <div style="border-top: 1px dashed var(--border-color); padding-top: 14px; display: flex; justify-content: space-between; align-items: baseline;">
                        <span style="font-size: 1.05rem; font-weight: 800; color: var(--savonia-navy);">Total Tagihan</span>
                        <span style="font-size: 1.45rem; font-weight: 800; color: var(--savonia-orange);">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; box-shadow: var(--shadow-glow);">
                    <span>Lanjut ke Checkout</span>
                    <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                </a>

                <div style="margin-top: 20px; background: #F8FAFC; padding: 14px; border-radius: var(--radius-md); font-size: 0.8rem; color: var(--text-secondary); text-align: center; border: 1px solid var(--border-color);">
                    🔒 Pembayaran aman otomatis dengan <strong>Midtrans Sandbox Snap</strong> (QRIS, VA BCA, Mandiri, BRI, dll.)
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: var(--radius-lg); border: 1.5px dashed var(--border-color); max-width: 600px; margin: 0 auto;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--savonia-gradient-subtle); color: var(--savonia-orange); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i data-lucide="shopping-bag" style="width: 40px; height: 40px;"></i>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 8px;">
                Keranjang Belanjamu Masih Kosong
            </h2>
            <p style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 28px; max-width: 420px; margin-left: auto; margin-right: auto;">
                Yuk, temukan Powerbank, TWS, IEM, Charger GaN, dan aksesoris teknologi favoritmu di katalog kami!
            </p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                <i data-lucide="sparkles" style="width: 18px; height: 18px;"></i>
                <span>Mulai Belanja Sekarang</span>
            </a>
        </div>
    @endif
</div>
@endsection
