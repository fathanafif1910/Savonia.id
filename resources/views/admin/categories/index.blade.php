@extends('layouts.admin')

@section('title', 'Kelola Kategori Aksesoris')

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 4px;">
        Kelola Kategori Aksesoris
    </h1>
    <p style="color: var(--text-muted); font-size: 0.9rem;">
        Atur pengelompokan jenis produk aksesoris teknologi dan gadget Savonia.id.
    </p>
</div>

<div style="display: grid; grid-template-columns: 360px 1fr; gap: 28px; align-items: start;">
    <!-- Form Tambah Kategori -->
    <div class="card">
        <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--savonia-navy); margin-bottom: 18px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
            Tambah Kategori Baru
        </h3>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Nama Kategori *</label>
                <input type="text" name="name" required placeholder="Contoh: Kacamata Aesthetic"
                    style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 700; font-size: 0.88rem; margin-bottom: 6px;">Deskripsi Singkat</label>
                <textarea name="description" rows="3" placeholder="Penjelasan singkat kategori..."
                    style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1.5px solid var(--border); font-size: 0.9rem;"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i>
                <span>Simpan Kategori</span>
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Jumlah Produk</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $index => $cat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong style="color: var(--savonia-navy);">{{ $cat->name }}</strong>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $cat->description }}</div>
                        </td>
                        <td><code>{{ $cat->slug }}</code></td>
                        <td>
                            <span class="badge" style="background: #EFF6FF; color: #1D4ED8;">
                                {{ $cat->products_count }} Produk
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" {{ $cat->products_count > 0 ? 'disabled title="Kategori masih memiliki produk"' : '' }}>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
