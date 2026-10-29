@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy);">Tambah Aksesoris Baru</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Masukkan rincian produk aksesoris untuk etalase Savonia.id.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline btn-sm">
            Kembali
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Kategori Aksesoris *</label>
                <select name="category_id" required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                    <option value="">Pilih Kategori...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Baseus GaN5 Pro 65W Fast Charger"
                    style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Harga Normal (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required placeholder="Contoh: 175000"
                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Harga Diskon (Rp, opsional)</label>
                    <input type="number" name="discount_price" value="{{ old('discount_price') }}" placeholder="Contoh: 149000"
                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Jumlah Stok (pcs) *</label>
                    <input type="number" name="stock" value="{{ old('stock', 25) }}" required min="0"
                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Berat Paket (gram) *</label>
                    <input type="number" name="weight_gram" value="{{ old('weight_gram', 150) }}" required min="1"
                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Bahan / Material</label>
                    <input type="text" name="material" value="{{ old('material', 'GaN / Fireproof PC / Braided Nylon') }}"
                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Warna / Varian</label>
                    <input type="text" name="color" value="{{ old('color', 'Matte Black / Space Gray') }}"
                        style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Link Foto Produk (URL Image)</label>
                <input type="url" name="image" value="{{ old('image', 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600&auto=format&fit=crop&q=80') }}"
                    placeholder="https://images.unsplash.com/..."
                    style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Deskripsi Lengkap Produk</label>
                <textarea name="description" rows="5" placeholder="Tuliskan spesifikasi, keunggulan bahan, dan detail aksesoris..."
                    style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">{{ old('description') }}</textarea>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; font-size: 0.9rem;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <span>Tampilkan sebagai Produk Unggulan / Terlaris di Beranda</span>
                </label>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                    <i data-lucide="check" style="width: 18px; height: 18px;"></i>
                    <span>Simpan Produk</span>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
