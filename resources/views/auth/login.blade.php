@extends('layouts.app')

@section('title', 'Masuk ke Akun - Savonia.id')

@section('content')
<div class="container section-padding" style="display: flex; justify-content: center; align-items: center; min-height: 75vh;">
    <div style="width: 100%; max-width: 440px; background: white; border-radius: var(--radius-lg); border: 1px solid var(--border-color); padding: 40px; box-shadow: var(--shadow-md);">
        <!-- Logo Header -->
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 56px; border-radius: 50%; margin: 0 auto 12px; box-shadow: var(--shadow-sm);">
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--savonia-navy);">Selamat Datang</h2>
            <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 4px;">
                Masuk ke akun <strong>Savonia.id</strong> Anda
            </p>
        </div>

        @if($errors->any())
            <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 12px 16px; border-radius: var(--radius-sm); font-size: 0.85rem; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy); margin-bottom: 6px;">
                    Alamat Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                    style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
            </div>

            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="font-size: 0.875rem; font-weight: 700; color: var(--savonia-navy);">
                        Kata Sandi
                    </label>
                </div>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                    style="width: 100%; padding: 11px 16px; border-radius: var(--radius-sm); border: 1.5px solid var(--border-color); font-size: 0.9rem;">
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; font-size: 0.85rem;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--text-secondary);">
                    <input type="checkbox" name="remember">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center; margin-bottom: 20px; box-shadow: var(--shadow-glow);">
                <span>Masuk Sekarang</span>
                <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </button>
        </form>

        <!-- Quick Login Demo Buttons (Untuk Kemudahan Uji Coba TA) -->
        <div style="background: #F8FAFC; border: 1px dashed var(--border-color); border-radius: var(--radius-sm); padding: 14px; margin-bottom: 20px; text-align: center;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                ⚡ Akses Cepat Akun Demo (Sidang TA)
            </div>
            <div style="display: flex; gap: 8px; justify-content: center;">
                <button type="button" onclick="fillAccount('admin@savonia.id', 'admin123')" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 4px 10px;">
                    👑 Admin
                </button>
                <button type="button" onclick="fillAccount('customer@gmail.com', 'password')" class="btn btn-outline btn-sm" style="font-size: 0.75rem; padding: 4px 10px;">
                    🛍️ Customer
                </button>
            </div>
        </div>

        <div style="text-align: center; font-size: 0.875rem; color: var(--text-secondary);">
            Belum punya akun? <a href="{{ route('register') }}" style="color: var(--savonia-orange); font-weight: 700;">Daftar di sini</a>
        </div>
    </div>
</div>

<script>
    function fillAccount(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
@endsection
