<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Toko Aksesoris Teknologi & Gadget') - Savonia.id</title>
    
    <!-- Favicon using uploaded logo -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Custom Style System -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    <!-- Midtrans Snap JS (Sandbox) -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key', env('MIDTRANS_CLIENT_KEY')) }}"></script>
        
    @stack('styles')
</head>
<body>

    <!-- TOP ANNOUNCEMENT BAR -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <span class="top-bar-badge">SAVONIA PROMO</span>
                <span>⚡ Aksesoris Teknologi Terlengkap — Powerbank, TWS, IEM, Charger GaN & Lebih Banyak Lagi!</span>
            </div>
            <div class="top-bar-right">
                <a href="{{ route('orders.track') }}"><i data-lucide="truck" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;"></i> Lacak Pesanan</a>
                <a href="https://wa.me/6281234567890" target="_blank"><i data-lucide="message-circle" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle;"></i> Bantuan CS</a>
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" style="color: #FFA500; font-weight: 700;">⚙️ Panel Admin</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <header class="navbar">
        <div class="container">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo Savonia.id">
                <span>SAVONIA<span class="dot-id">.ID</span></span>
            </a>

            <!-- Search Bar -->
            <div class="nav-search">
                <form action="{{ route('catalog.index') }}" method="GET">
                    <i data-lucide="search" class="search-icon" style="width: 18px; height: 18px;"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari powerbank, TWS, IEM, charger GaN, case iPhone...">
                </form>
            </div>

            <!-- Navigation Links -->
            <ul class="nav-menu">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('catalog.index') }}" class="nav-link {{ request()->routeIs('catalog.index') && !request('category') ? 'active' : '' }}">Katalog</a></li>
                <li><a href="{{ route('catalog.index', ['featured' => 1]) }}" class="nav-link {{ request('featured') ? 'active' : '' }}">Terlaris</a></li>
                <li><a href="{{ route('orders.track') }}" class="nav-link {{ request()->routeIs('orders.track') ? 'active' : '' }}">Lacak Order</a></li>
            </ul>

            <!-- Navigation Actions -->
            <div class="nav-actions">
                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="cart-btn" title="Keranjang Belanja">
                    <i data-lucide="shopping-bag" style="width: 20px; height: 20px;"></i>
                    @php
                        $cartCount = Auth::check() 
                            ? \App\Models\Cart::where('user_id', Auth::id())->sum('quantity')
                            : (session('cart') ? count(session('cart')) : 0);
                    @endphp
                    <span class="cart-badge" id="cart-counter">{{ $cartCount }}</span>
                </a>

                <!-- Auth Buttons / User Profile -->
                @auth
                    <div style="position: relative;" id="user-dropdown-wrapper">
                        <button class="user-menu-btn" onclick="toggleUserDropdown()">
                            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            <span style="max-width: 110px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ Auth::user()->name }}</span>
                            <i data-lucide="chevron-down" style="width: 14px; height: 14px;"></i>
                        </button>
                        
                        <div id="user-dropdown" style="display: none; position: absolute; right: 0; top: 120%; width: 200px; background: white; border-radius: 12px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); padding: 8px 0; z-index: 200;">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 0.875rem; color: #FF6600; font-weight: 700;">
                                    <i data-lucide="layout-dashboard" style="width: 16px; height: 16px;"></i> Dashboard Admin
                                </a>
                                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 4px 0;">
                            @endif
                            <a href="{{ route('orders.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 0.875rem; color: var(--text-primary);">
                                <i data-lucide="receipt" style="width: 16px; height: 16px;"></i> Pesanan Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 18px; font-size: 0.875rem; color: var(--danger); background: none; border: none; cursor: pointer; text-align: left;">
                                    <i data-lucide="log-out" style="width: 16px; height: 16px;"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- NOTIFICATION FLASH ALERTS -->
    <div class="container" style="margin-top: 20px;">
        @if(session('success'))
            <div style="background: #ECFDF5; border: 1.5px solid #A7F3D0; color: #065F46; padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                    <i data-lucide="check-circle-2" style="width: 20px; height: 20px; color: #10B981;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #065F46;">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div style="background: #FEF2F2; border: 1.5px solid #FECACA; color: #991B1B; padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                    <i data-lucide="alert-circle" style="width: 20px; height: 20px; color: #EF4444;"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #991B1B;">&times;</button>
            </div>
        @endif
    </div>

    <!-- MAIN PAGE CONTENT -->
    <main style="flex: 1;">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Col 1: About -->
                <div>
                    <div class="footer-brand-title">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 38px; border-radius: 50%;">
                        <span>SAVONIA<span style="color: var(--savonia-orange-light);">.ID</span></span>
                    </div>
                    <p class="footer-desc">
                        Toko aksesoris teknologi & gadget terlengkap. Menyediakan Powerbank, iPhone Accessories, Laptop Accessories, TWS, IEM, Charger GaN, Adaptor/Cable, Electric Grooming & Gaming Support berkualitas premium.
                    </p>
                </div>

                <!-- Col 2: Kategori Aksesoris -->
                <div>
                    <h4 class="footer-heading">Kategori Populer</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('catalog.index', ['category' => 'tws']) }}">TWS Earbuds</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'iem']) }}">IEM Audiophile</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'powerbank']) }}">Powerbank</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'adaptor-cable']) }}">Adaptor & Kabel</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'iphone-accessories']) }}">iPhone Accessories</a></li>
                        <li><a href="{{ route('catalog.index', ['category' => 'gaming-support']) }}">Gaming Support</a></li>
                    </ul>
                </div>

                <!-- Col 3: Layanan & Informasi -->
                <div>
                    <h4 class="footer-heading">Layanan Pelanggan</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('orders.track') }}">Lacak Status Pesanan</a></li>
                        <li><a href="{{ route('cart.index') }}">Keranjang Belanja</a></li>
                        <li><a href="{{ route('catalog.index') }}">Garansi & Pengembalian</a></li>
                        <li><a href="{{ route('catalog.index') }}">Panduan Pilih TWS/IEM</a></li>
                        <li><a href="{{ route('login') }}">Akun Pengguna</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak -->
                <div>
                    <h4 class="footer-heading">Informasi Kontak</h4>
                    <p style="font-size: 0.85rem; line-height: 1.6; margin-bottom: 14px;">
                        📍 Workshop: Punge Ujong, Kec.Meuraxa, Kota Banda Aceh, Aceh<br>
                        📞 WhatsApp CS: 0852-7700-0063
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} <strong>SAVONIA.ID</strong> - Rancang Bangun Sistem Informasi Penjualan Aksesoris Berbasis Web. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();

        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        // Close dropdown when clicked outside
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('user-dropdown-wrapper');
            const dropdown = document.getElementById('user-dropdown');
            if (wrapper && dropdown && !wrapper.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
