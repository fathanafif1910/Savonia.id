@extends('layouts.app')

@section('title', 'Savonia.id - Toko Aksesoris Teknologi & Gadget Terlengkap | Powerbank, TWS, IEM, Charger GaN')

@section('content')

<!-- ==========================================================================
     HERO SECTION
     ========================================================================== -->
<section class="hero-section">
    <div class="container">
        <div class="hero-grid">
            <!-- Hero Left Content -->
            <div>
                <div class="hero-tag">
                    <i data-lucide="zap" style="width: 16px; height: 16px;"></i>
                    <span>KOLEKSI AKSESORIS TEKNOLOGI TERBARU</span>
                </div>
                
                <h1 class="hero-title">
                    Lengkapi Gadget-mu dengan <span class="gradient-text">Aksesoris Premium Terbaik</span>
                </h1>
                
                <p class="hero-desc">
                    Temukan Powerbank fast charge, TWS ANC, IEM audiophile, charger GaN, dan aksesoris teknologi berkualitas premium di <strong>Savonia.id</strong>. Produk terpilih, harga terjangkau, pengiriman cepat!
                </p>
                
                <div class="hero-buttons">
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                        <i data-lucide="shopping-bag" style="width: 18px; height: 18px;"></i>
                        <span>Belanja Sekarang</span>
                    </a>
                    <a href="#kategori" class="btn btn-outline btn-lg">
                        <span>Pilih Kategori</span>
                        <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
                    </a>
                </div>

                <div class="hero-features">
                    <div class="hero-feat-item">
                        <div class="hero-feat-icon"><i data-lucide="shield-check" style="width: 18px; height: 18px;"></i></div>
                        <span>Produk Original</span>
                    </div>
                    <div class="hero-feat-item">
                        <div class="hero-feat-icon"><i data-lucide="zap" style="width: 18px; height: 18px;"></i></div>
                        <span>Midtrans Instant Pay</span>
                    </div>
                    <div class="hero-feat-item">
                        <div class="hero-feat-icon"><i data-lucide="headphones" style="width: 18px; height: 18px;"></i></div>
                        <span>TWS & IEM Expert</span>
                    </div>
                </div>
            </div>

            <!-- Hero Right Showcase -->
            <div class="hero-card">
                <div class="hero-image-wrap">
                    <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80" alt="Savonia Tech Accessories Showcase">
                    
                    <div class="floating-badge">
                        <div class="floating-badge-icon">
                            <i data-lucide="award" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 1.1rem; color: var(--savonia-navy);">Premium Quality</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted);">Dipercaya Ribuan Pembeli</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     CATEGORIES SECTION
     ========================================================================== -->
<section class="section-padding" id="kategori">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-subtitle">JELAJAHI KOLEKSI</div>
                <h2 class="section-title">Kategori Aksesoris Teknologi</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline btn-sm">
                <span>Lihat Semua</span>
                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
            </a>
        </div>

        <div class="category-grid">
            @foreach($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="category-card">
                    <div class="category-icon">
                        <i data-lucide="{{ $category->icon ?? 'package' }}" style="width: 26px; height: 26px;"></i>
                    </div>
                    <span class="category-name">{{ $category->name }}</span>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $category->products_count }} Produk</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ==========================================================================
     PROMO / FLASH SALE BANNER
     ========================================================================== -->
@if($flashSaleProducts->count() > 0)
<section class="section-padding" style="background: white; border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
    <div class="container">
        <!-- Banner Header -->
        <div style="background: var(--savonia-gradient); border-radius: var(--radius-lg); padding: 32px 36px; color: white; display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; box-shadow: var(--shadow-glow); flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="background: rgba(255,255,255,0.22); padding: 4px 12px; border-radius: var(--radius-full); font-size: 0.78rem; font-weight: 800; letter-spacing: 1px;">
                    🔥 SAVONIA FLASH SALE
                </span>
                <h3 style="font-size: 1.85rem; font-weight: 800; margin-top: 8px;">Diskon Spesial Aksesoris Tech</h3>
                <p style="color: rgba(255,255,255,0.9); font-size: 0.95rem;">Hemat hingga 30% untuk TWS, IEM, Charger GaN & Powerbank pilihan!</p>
            </div>
            <a href="{{ route('catalog.index', ['sort' => 'termurah']) }}" class="btn" style="background: white; color: var(--savonia-navy); font-weight: 700; padding: 12px 24px; box-shadow: var(--shadow-sm);">
                Lihat Semua Promo
            </a>
        </div>

        <div class="products-grid">
            @foreach($flashSaleProducts as $product)
                <div class="product-card">
                    <div class="product-thumb">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                        @if($product->has_discount)
                            <span class="badge-discount">HEMAT {{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <span class="product-category-tag">{{ $product->category->name }}</span>
                        <a href="{{ route('catalog.show', $product->slug) }}" class="product-title">
                            {{ $product->name }}
                        </a>
                        <div class="product-specs">
                            <span><i data-lucide="layers" style="width: 12px; height: 12px; display: inline-block;"></i> {{ Str::limit($product->material, 24) }}</span>
                        </div>
                        <div class="product-price-row">
                            <span class="price-final">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                            @if($product->has_discount)
                                <span class="price-original">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-outline btn-add-cart">
                            <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                            <span>Detail Produk</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ==========================================================================
     FEATURED PRODUCTS (TERLARIS)
     ========================================================================== -->
<section class="section-padding">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-subtitle">PALING BANYAK DICARI</div>
                <h2 class="section-title">Produk Unggulan Savonia.id</h2>
            </div>
            <a href="{{ route('catalog.index', ['featured' => 1]) }}" class="btn btn-outline btn-sm">
                <span>Lihat Selengkapnya</span>
                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
            </a>
        </div>

        <div class="products-grid">
            @foreach($featuredProducts as $product)
                <div class="product-card">
                    <div class="product-thumb">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                        @if($product->has_discount)
                            <span class="badge-discount">-{{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <span class="product-category-tag">{{ $product->category->name }}</span>
                        <a href="{{ route('catalog.show', $product->slug) }}" class="product-title">
                            {{ $product->name }}
                        </a>
                        <div class="product-specs">
                            <span>⚡ {{ Str::limit($product->material, 26) }}</span>
                        </div>
                        <div class="product-price-row">
                            <span class="price-final">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                            @if($product->has_discount)
                                <span class="price-original">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-add-cart">
                                <i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
                                <span>+ Keranjang</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ==========================================================================
     BRAND TRUST BANNER
     ========================================================================== -->
<section class="section-padding" style="background: var(--savonia-navy); padding: 40px 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 28px;">
            <p style="color: #94A3B8; font-size: 0.82rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">Brand yang Tersedia di Savonia.id</p>
        </div>
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 16px 32px; align-items: center;">
            @foreach(['Baseus', 'Anker', 'Ugreen', 'Spigen', 'KZ', 'Moondrop', 'Soundcore', 'Edifier', 'Xiaomi', 'Fantech'] as $brand)
                <span style="color: #475569; font-weight: 800; font-size: 1rem; letter-spacing: 0.5px; padding: 8px 16px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">{{ $brand }}</span>
            @endforeach
        </div>
    </div>
</section>

<!-- ==========================================================================
     LATEST PRODUCTS
     ========================================================================== -->
<section class="section-padding" style="background: white;">
    <div class="container">
        <div class="section-header">
            <div>
                <div class="section-subtitle">BARU MASUK</div>
                <h2 class="section-title">Produk Terbaru</h2>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-outline btn-sm">
                <span>Lihat Katalog</span>
                <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
            </a>
        </div>
        <div class="products-grid">
            @foreach($latestProducts->take(8) as $product)
                <div class="product-card">
                    <div class="product-thumb">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
                        @if($product->has_discount)
                            <span class="badge-discount">-{{ $product->discount_percent }}%</span>
                        @endif
                    </div>
                    <div class="product-body">
                        <span class="product-category-tag">{{ $product->category->name }}</span>
                        <a href="{{ route('catalog.show', $product->slug) }}" class="product-title">
                            {{ $product->name }}
                        </a>
                        <div class="product-specs">
                            <span>{{ Str::limit($product->material, 26) }}</span>
                        </div>
                        <div class="product-price-row">
                            <span class="price-final">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                            @if($product->has_discount)
                                <span class="price-original">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-add-cart">
                                <i data-lucide="shopping-bag" style="width: 16px; height: 16px;"></i>
                                <span>+ Keranjang</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ==========================================================================
     WHY SAVONIA SECTION (VALUE PROPOSITIONS)
     ========================================================================== -->
<section class="section-padding" style="background: #F8FAFC; border-top: 1px solid var(--border-color);">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto 48px;">
            <div class="section-subtitle">KENAPA MEMILIH KAMI</div>
            <h2 class="section-title">Komitmen Kualitas Savonia.id</h2>
            <p style="color: var(--text-secondary); margin-top: 8px;">
                Kami memastikan setiap aksesoris teknologi yang Anda pesan adalah produk berkualitas dengan layanan terbaik.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px;">
            <div style="background: white; padding: 28px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                    <i data-lucide="shield-check" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Produk 100% Original</h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Semua produk bersumber dari distributor resmi & bergaransi. Anti KW, anti tipu-tipu.
                </p>
            </div>

            <div style="background: white; padding: 28px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                    <i data-lucide="package-check" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Packing Aman & Rapi</h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Setiap paket dikemas dengan bubble wrap ekstra tebal & kardus kokoh agar aman sampai tujuan.
                </p>
            </div>

            <div style="background: white; padding: 28px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                    <i data-lucide="credit-card" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Pembayaran Midtrans</h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Transaksi terenkripsi otomatis via QRIS, GoPay, ShopeePay, dan Virtual Account bank utama.
                </p>
            </div>

            <div style="background: white; padding: 28px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                    <i data-lucide="headphones" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Konsultasi TWS & IEM</h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Bingung pilih earphone? Tim kami siap membantu rekomendasikan TWS/IEM sesuai kebutuhan & budget.
                </p>
            </div>

            <div style="background: white; padding: 28px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                    <i data-lucide="truck" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Lacak Resi Real-time</h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Cek posisi paket kapan saja langsung dari website kami dengan nomor resi kurir pilihan.
                </p>
            </div>

            <div style="background: white; padding: 28px 24px; border-radius: var(--radius-md); border: 1px solid var(--border-color); text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; box-shadow: var(--shadow-sm);">
                    <i data-lucide="battery-charging" style="width: 28px; height: 28px;"></i>
                </div>
                <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Spesialis Powerbank & GaN</h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.5;">
                    Koleksi powerbank & charger GaN terlengkap: dari yang compact 10.000mAh hingga desktop 140W.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
