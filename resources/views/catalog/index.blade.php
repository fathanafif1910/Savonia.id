@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name : 'Semua Aksesoris') . ' - Savonia.id')

@section('content')
<div class="container section-padding">
    <!-- Breadcrumb -->
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
        <a href="{{ route('home') }}" style="color: var(--text-secondary);">Beranda</a>
        <span>/</span>
        <a href="{{ route('catalog.index') }}" style="color: var(--text-secondary);">Katalog</a>
        @if($selectedCategory)
            <span>/</span>
            <span style="color: var(--savonia-orange); font-weight: 600;">{{ $selectedCategory->name }}</span>
        @endif
    </div>

    <!-- Catalog Header -->
    <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <div>
            <h1 style="font-size: 2.2rem; font-weight: 800; color: var(--savonia-navy); letter-spacing: -0.5px;">
                {{ $selectedCategory ? $selectedCategory->name : 'Semua Koleksi Aksesoris' }}
            </h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem; margin-top: 4px;">
                Menampilkan {{ $products->total() }} pilihan aksesoris teknologi & gadget berkualitas
            </p>
        </div>

        <!-- Sort Form -->
        <form action="{{ route('catalog.index') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
            @if(request('featured')) <input type="hidden" name="featured" value="{{ request('featured') }}"> @endif
            
            <label for="sort" style="font-size: 0.875rem; font-weight: 600; color: var(--text-secondary);">Urutkan:</label>
            <select name="sort" id="sort" onchange="this.form.submit()" style="padding: 8px 14px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); background: white; font-size: 0.875rem; font-weight: 600; color: var(--savonia-navy); cursor: pointer;">
                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="terlaris" {{ request('sort') == 'terlaris' ? 'selected' : '' }}>Paling Populer</option>
                <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
            </select>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 36px; align-items: start;">
        <!-- SIDEBAR FILTERS -->
        <aside style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 24px; position: sticky; top: 96px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--savonia-navy);">Filter Kategori</h3>
                @if(request('category') || request('q') || request('featured'))
                    <a href="{{ route('catalog.index') }}" style="font-size: 0.78rem; color: var(--savonia-orange); font-weight: 600;">Reset</a>
                @endif
            </div>

            <!-- Categories List -->
            <ul style="list-style: none; display: flex; flex-direction: column; gap: 8px; margin-bottom: 28px;">
                <li>
                    <a href="{{ route('catalog.index') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: var(--radius-sm); font-size: 0.9rem; font-weight: {{ !request('category') ? '700' : '500' }}; background: {{ !request('category') ? 'rgba(255,102,0,0.1)' : 'transparent' }}; color: {{ !request('category') ? 'var(--savonia-orange)' : 'var(--text-secondary)' }};">
                        <span>Semua Aksesoris</span>
                        <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-radius: var(--radius-sm); font-size: 0.9rem; font-weight: {{ request('category') == $cat->slug ? '700' : '500' }}; background: {{ request('category') == $cat->slug ? 'rgba(255,102,0,0.1)' : 'transparent' }}; color: {{ request('category') == $cat->slug ? 'var(--savonia-orange)' : 'var(--text-secondary)' }};">
                            <span>{{ $cat->name }}</span>
                            <span style="font-size: 0.75rem; color: var(--text-muted); background: #F1F5F9; padding: 2px 8px; border-radius: 10px;">{{ $cat->products_count }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Promo Quick Filter -->
            <div style="border-top: 1px solid var(--border-color); padding-top: 20px;">
                <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 12px;">Promo Khusus</h4>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a href="{{ route('catalog.index', ['featured' => 1]) }}" style="display: flex; align-items: center; gap: 8px; font-size: 0.875rem; color: {{ request('featured') ? 'var(--savonia-orange)' : 'var(--text-secondary)' }}; font-weight: {{ request('featured') ? '700' : '500' }};">
                        <i data-lucide="award" style="width: 16px; height: 16px;"></i>
                        <span>Produk Paling Laris</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- MAIN PRODUCTS GRID -->
        <div>
            @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
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
                                    <span>✨ {{ Str::limit($product->material, 24) }}</span>
                                </div>
                                <div class="product-price-row">
                                    <span class="price-final">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                                    @if($product->has_discount)
                                        <span class="price-original">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-outline" style="flex: 1; padding: 8px; font-size: 0.825rem;">
                                        Lihat
                                    </a>
                                    <form action="{{ route('cart.add') }}" method="POST" style="flex: 2;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 8px; font-size: 0.825rem;">
                                            <i data-lucide="shopping-bag" style="width: 14px; height: 14px;"></i>
                                            <span>+ Keranjang</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 40px; display: flex; justify-content: center;">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 60px 20px; background: white; border-radius: var(--radius-md); border: 1.5px dashed var(--border-color);">
                    <i data-lucide="package-search" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 14px;"></i>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 8px;">Tidak Ada Produk Ditemukan</h3>
                    <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">
                        Coba gunakan kata kunci pencarian lain atau pilih kategori aksesoris yang berbeda.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-sm">Lihat Semua Produk</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
