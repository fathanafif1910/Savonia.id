@extends('layouts.app')

@section('title', $product->name . ' - Savonia.id')

@section('content')
<div class="container section-padding">
    <!-- Breadcrumb -->
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 28px; display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('home') }}" style="color: var(--text-secondary);">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" style="color: var(--text-secondary);">Katalog</a>
        <span>/</span>
        <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" style="color: var(--text-secondary);">{{ $product->category->name }}</a>
        <span>/</span>
        <span style="color: var(--savonia-orange); font-weight: 600;">{{ $product->name }}</span>
    </div>

    <!-- Product Showcase (2 Columns) -->
    <div style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 50px; background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 40px; box-shadow: var(--shadow-sm); margin-bottom: 50px;">
        <!-- Left: Image Gallery -->
        <div>
            <div style="position: relative; border-radius: var(--radius-md); overflow: hidden; background: #F8FAFC; border: 1px solid var(--border-color);">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" id="main-product-img" style="width: 100%; height: 480px; object-fit: cover;">
                @if($product->has_discount)
                    <span class="badge-discount" style="font-size: 0.85rem; padding: 6px 14px;">
                        HEMAT {{ $product->discount_percent }}%
                    </span>
                @endif
            </div>
            
            <div style="display: flex; gap: 12px; margin-top: 16px;">
                <div style="width: 80px; height: 80px; border-radius: 8px; border: 2px solid var(--savonia-orange); overflow: hidden; cursor: pointer;">
                    <img src="{{ $product->image }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>

        <!-- Right: Details & Order Box -->
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <span class="product-category-tag" style="font-size: 0.85rem;">{{ $product->category->name }}</span>
                <span style="color: var(--text-muted);">&bull;</span>
                <span style="font-size: 0.825rem; color: var(--text-muted);">SKU: {{ $product->sku }}</span>
            </div>

            <h1 style="font-size: 2.1rem; font-weight: 800; color: var(--savonia-navy); line-height: 1.3; margin-bottom: 16px;">
                {{ $product->name }}
            </h1>

            <!-- Ratings -->
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 22px;">
                <div style="display: flex; color: #F59E0B; gap: 2px;">
                    @for($i = 1; $i <= 5; $i++)
                        <i data-lucide="star" style="width: 16px; height: 16px; fill: #F59E0B;"></i>
                    @endfor
                </div>
                <span style="font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy);">{{ number_format($product->average_rating, 1) }}</span>
                <span style="color: var(--text-muted); font-size: 0.85rem;">({{ $product->reviews->count() }} ulasan pembeli)</span>
            </div>

            <!-- Price Card -->
            <div style="background: var(--savonia-gradient-subtle); padding: 18px 24px; border-radius: var(--radius-md); border: 1px solid rgba(255,102,0,0.15); margin-bottom: 24px; display: flex; align-items: baseline; gap: 14px;">
                <span style="font-size: 2.2rem; font-weight: 800; color: var(--savonia-navy);">
                    Rp {{ number_format($product->final_price, 0, ',', '.') }}
                </span>
                @if($product->has_discount)
                    <span style="font-size: 1.15rem; color: var(--text-muted); text-decoration: line-through;">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span style="background: var(--savonia-gradient); color: white; padding: 2px 8px; border-radius: var(--radius-full); font-size: 0.75rem; font-weight: 700;">
                        Diskon {{ $product->discount_percent }}%
                    </span>
                @endif
            </div>

            <!-- Specs Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 28px; background: #F8FAFC; padding: 18px; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Material / Bahan</span>
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--savonia-navy); margin-top: 2px;">
                        {{ $product->material ?? 'Premium Quality' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Warna / Varian</span>
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--savonia-navy); margin-top: 2px;">
                        {{ $product->color ?? 'Standar' }}
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Berat Pengiriman</span>
                    <div style="font-weight: 700; font-size: 0.9rem; color: var(--savonia-navy); margin-top: 2px;">
                        {{ $product->weight_gram }} gram
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase;">Status Ketersediaan</span>
                    <div style="font-weight: 700; font-size: 0.9rem; color: {{ $product->stock > 0 ? 'var(--success)' : 'var(--danger)' }}; margin-top: 2px;">
                        {{ $product->stock > 0 ? 'Tersedia (' . $product->stock . ' pcs)' : 'Stok Habis' }}
                    </div>
                </div>
            </div>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div style="display: flex; align-items: center; gap: 18px; margin-bottom: 24px;">
                    <label style="font-weight: 700; font-size: 0.95rem; color: var(--savonia-navy);">Jumlah:</label>
                    <div style="display: flex; align-items: center; border: 1.5px solid var(--border-color); border-radius: var(--radius-full); overflow: hidden; background: white;">
                        <button type="button" onclick="decrementQty()" style="padding: 8px 16px; background: none; border: none; cursor: pointer; font-weight: 700; font-size: 1.1rem; color: var(--savonia-navy);">-</button>
                        <input type="number" id="qty-input" name="quantity" value="1" min="1" max="{{ $product->stock }}" style="width: 50px; text-align: center; border: none; font-weight: 700; font-size: 1rem; color: var(--savonia-navy);" readonly>
                        <button type="button" onclick="incrementQty({{ $product->stock }})" style="padding: 8px 16px; background: none; border: none; cursor: pointer; font-weight: 700; font-size: 1.1rem; color: var(--savonia-navy);">+</button>
                    </div>
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Maksimal {{ $product->stock }} item</span>
                </div>

                <div style="display: flex; gap: 14px;">
                    <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; justify-content: center;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <i data-lucide="shopping-bag" style="width: 20px; height: 20px;"></i>
                        <span>Masukkan ke Keranjang</span>
                    </button>
                </div>
            </form>

            <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-color); display: flex; align-items: center; gap: 20px; font-size: 0.825rem; color: var(--text-secondary);">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i data-lucide="shield-check" style="width: 16px; height: 16px; color: var(--success);"></i>
                    <span>Garansi Realpic 100%</span>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <i data-lucide="zap" style="width: 16px; height: 16px; color: var(--savonia-orange);"></i>
                    <span>Midtrans Sandbox Verified</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Description & Reviews Tabs -->
    <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 40px; box-shadow: var(--shadow-sm); margin-bottom: 50px;">
        <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 20px; border-bottom: 2px solid var(--border-color); padding-bottom: 12px;">
            Deskripsi & Detail Produk
        </h3>
        
        <div style="line-height: 1.8; color: var(--text-secondary); white-space: pre-line; font-size: 0.95rem;">
            {{ $product->description }}
        </div>

        <div style="margin-top: 36px; padding: 24px; background: #FFF9F5; border-radius: var(--radius-md); border-left: 4px solid var(--savonia-orange);">
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">
                💡 Tips Penggunaan & Perawatan
            </h4>
            <ul style="padding-left: 20px; font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
                <li>Simpan produk di dalam kotak atau pouch yang disertakan untuk melindungi dari debu dan benturan.</li>
                <li>Untuk earphone/TWS/IEM, bersihkan eartip secara rutin dengan kain lembab atau tisu alkohol ringan.</li>
                <li>Untuk kabel, gulung dengan teknik figure-8 (bukan melingkar ketat) agar kabel tidak cepat rusak.</li>
                <li>Untuk powerbank & charger, jauhkan dari paparan panas langsung dan kelembapan tinggi.</li>
            </ul>
        </div>
    </div>

    <!-- Customer Reviews -->
    @if($product->reviews->count() > 0)
    <div style="background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 40px; box-shadow: var(--shadow-sm); margin-bottom: 50px;">
        <h3 style="font-size: 1.4rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 24px;">
            Ulasan Pembeli ({{ $product->reviews->count() }})
        </h3>
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($product->reviews as $review)
                <div style="padding-bottom: 20px; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span style="font-weight: 700; color: var(--savonia-navy); font-size: 0.9rem;">{{ $review->user->name }}</span>
                                <span style="font-size: 0.75rem; color: var(--success); margin-left: 6px; font-weight: 600;">✓ Pembeli Terverifikasi</span>
                            </div>
                        </div>
                        <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="display: flex; color: #F59E0B; gap: 2px; margin-bottom: 8px;">
                        @for($i = 1; $i <= $review->rating; $i++)
                            <i data-lucide="star" style="width: 14px; height: 14px; fill: #F59E0B;"></i>
                        @endfor
                    </div>
                    <p style="font-size: 0.9rem; color: var(--text-secondary); line-height: 1.5;">
                        {{ $review->comment }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div style="margin-top: 60px;">
        <h3 style="font-size: 1.6rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 24px;">
            Aksesoris Serupa yang Mungkin Kamu Suka
        </h3>
        <div class="products-grid">
            @foreach($relatedProducts as $rel)
                <div class="product-card">
                    <div class="product-thumb">
                        <img src="{{ $rel->image }}" alt="{{ $rel->name }}" loading="lazy">
                        @if($rel->has_discount)
                            <span class="badge-discount">-{{ $rel->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <span class="product-category-tag">{{ $rel->category->name }}</span>
                        <a href="{{ route('catalog.show', $rel->slug) }}" class="product-title">
                            {{ $rel->name }}
                        </a>
                        <div class="product-price-row">
                            <span class="price-final">Rp {{ number_format($rel->final_price, 0, ',', '.') }}</span>
                            @if($rel->has_discount)
                                <span class="price-original">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('catalog.show', $rel->slug) }}" class="btn btn-outline btn-add-cart">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function incrementQty(max) {
        const input = document.getElementById('qty-input');
        let current = parseInt(input.value) || 1;
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decrementQty() {
        const input = document.getElementById('qty-input');
        let current = parseInt(input.value) || 1;
        if (current > 1) {
            input.value = current - 1;
        }
    }
</script>
@endsection
