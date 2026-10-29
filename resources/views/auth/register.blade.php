@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Savonia.id')

@section('content')
<div class="container section-padding" style="display: flex; justify-content: center; align-items: center; min-height: 80vh;">
    <div style="width: 100%; max-width: 520px; background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 40px; box-shadow: var(--shadow-md);">
        <!-- Logo Header -->
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 56px; border-radius: 50%; margin: 0 auto 12px; box-shadow: var(--shadow-sm);">
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--savonia-navy);">Buat Akun Pelanggan</h2>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 4px;">
                Bergabung dengan <strong>Savonia.id</strong> dan nikmati promo aksesoris eksklusif!
            </p>
        </div>

        @if($errors->any())
            <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 0.85rem; margin-bottom: 20px;">
                <ul style="padding-left: 18px; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                    Nama Lengkap <span style="color: var(--danger);">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Sarah Amalia"
                    style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                    Alamat Email <span style="color: var(--danger);">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                    style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Kata Sandi <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="password" name="password" required placeholder="Min. 6 karakter"
                        style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Ulangi Sandi <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required placeholder="Konfirmasi sandi"
                        style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Nomor WhatsApp / HP
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                        style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                        Kota Domisili
                    </label>
                    <input type="text" name="city" value="{{ old('city') }}" placeholder="Kota"
                        style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; margin-top: 10px; margin-bottom: 20px; box-shadow: var(--shadow-glow);">
                <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                <span>Daftar Akun Sekarang</span>
            </button>
        </form>

        <div style="text-align: center; font-size: 0.875rem; color: var(--text-secondary);">
            Sudah memiliki akun? <a href="{{ route('login') }}" style="color: var(--savonia-orange); font-weight: 700;">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
