@extends('layouts.admin')

@section('title', 'Kelola Produk Aksesoris')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 4px;">
            Daftar Produk Aksesoris
        </h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">
            Kelola katalog aksesoris teknologi, gadget, audio, dan periferal Savonia.id.
        </p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i data-lucide="plus" style="width: 18px; height: 18px;"></i>
        <span>Tambah Produk Baru</span>
    </a>
</div>

<!-- Filters -->
<div class="card" style="padding: 18px 24px;">
    <form action="{{ route('admin.products.index') }}" method="GET" style="display: flex; gap: 14px; flex-wrap: wrap;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk atau SKU..."
            style="flex: 1; min-width: 220px; padding: 9px 16px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.88rem;">
        
        <select name="category_id" style="padding: 9px 16px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.88rem; background: white;">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-outline">
            <i data-lucide="search" style="width: 16px; height: 16px;"></i>
            <span>Filter</span>
        </button>
    </form>
</div>

<!-- Products Table -->
<div class="card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Bahan</th>
                    <th>Unggulan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border);">
                        </td>
                        <td>
                            <strong style="color: var(--savonia-navy); display: block;">{{ $product->name }}</strong>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">SKU: {{ $product->sku }}</span>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <strong style="color: var(--savonia-orange);">Rp {{ number_format($product->final_price, 0, ',', '.') }}</strong>
                            @if($product->has_discount)
                                <div style="font-size: 0.75rem; color: var(--text-muted); text-decoration: line-through;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="{{ $product->stock <= 15 ? 'background: #FEE2E2; color: #B91C1C;' : 'background: #ECFDF5; color: #047857;' }}">
                                {{ $product->stock }} pcs
                            </span>
                        </td>
                        <td>{{ Str::limit($product->material, 20) }}</td>
                        <td>
                            @if($product->is_featured)
                                <span class="badge" style="background: #FEF3C7; color: #B45309;">★ Unggulan</span>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline btn-sm">
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Tidak ada produk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px;">
        {{ $products->links() }}
    </div>
</div>
@endsection
