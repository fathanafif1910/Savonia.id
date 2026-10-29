<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Savonia.id</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons & Chart.js -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --savonia-orange: #FF6600;
            --savonia-gradient: linear-gradient(135deg, #FF9500 0%, #FF5500 50%, #EE1D23 100%);
            --sidebar-bg: #16192B;
            --sidebar-hover: #222640;
            --bg-content: #F8FAFC;
            --border: #E2E8F0;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --radius-md: 12px;
            --radius-lg: 18px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-content);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: #94A3B8;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            border-right: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            text-decoration: none;
        }
        .sidebar-brand img {
            height: 38px;
            border-radius: 50%;
        }
        .sidebar-menu {
            list-style: none;
            padding: 20px 14px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            overflow-y: auto;
        }
        .menu-category {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
            padding: 12px 12px 6px;
        }
        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        .menu-link:hover {
            color: white;
            background: var(--sidebar-hover);
        }
        .menu-link.active {
            color: white;
            background: var(--savonia-gradient);
            box-shadow: 0 4px 14px rgba(255, 102, 0, 0.35);
        }
        .sidebar-user {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .admin-topbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-content {
            padding: 32px;
            flex: 1;
        }

        /* Common Elements */
        .card {
            background: white;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-primary {
            background: var(--savonia-gradient);
            color: white;
        }
        .btn-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }
        .btn-outline {
            background: white;
            border: 1.5px solid var(--border);
            color: var(--text-dark);
        }
        .btn-outline:hover {
            border-color: var(--savonia-orange);
            color: var(--savonia-orange);
        }
        .btn-danger {
            background: #EF4444;
            color: white;
        }
        .btn-sm {
            padding: 5px 12px;
            font-size: 0.78rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #F8FAFC;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            padding: 12px 16px;
            border-bottom: 1.5px solid var(--border);
            text-align: left;
        }
        td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            font-size: 0.875rem;
            vertical-align: middle;
        }
        tr:hover td {
            background: #F8FAFC;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo">
            <span>SAVONIA <span style="color: #FF8800; font-size: 0.8em;">ADMIN</span></span>
        </a>

        <ul class="sidebar-menu">
            <li class="menu-category">Utama</li>
            <li>
                <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" style="width: 18px; height: 18px;"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="menu-category">Manajemen Penjualan</li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="menu-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i data-lucide="shopping-cart" style="width: 18px; height: 18px;"></i>
                    <span>Kelola Pesanan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}" class="menu-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3" style="width: 18px; height: 18px;"></i>
                    <span>Laporan Penjualan (TA)</span>
                </a>
            </li>

            <li class="menu-category">Katalog Produk</li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="menu-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i data-lucide="package" style="width: 18px; height: 18px;"></i>
                    <span>Produk Aksesoris</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="menu-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i data-lucide="tags" style="width: 18px; height: 18px;"></i>
                    <span>Kategori Aksesoris</span>
                </a>
            </li>

            <li class="menu-category">Toko & Navigasi</li>
            <li>
                <a href="{{ route('home') }}" target="_blank" class="menu-link">
                    <i data-lucide="external-link" style="width: 18px; height: 18px;"></i>
                    <span>Buka Web Toko</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-user">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--savonia-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                    A
                </div>
                <div>
                    <div style="color: white; font-weight: 700; font-size: 0.85rem;">{{ Auth::user()->name }}</div>
                    <div style="font-size: 0.72rem; color: #64748B;">Super Admin</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="background: none; border: none; color: #94A3B8; cursor: pointer;" title="Logout">
                    <i data-lucide="log-out" style="width: 18px; height: 18px;"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN BODY -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div style="font-size: 0.9rem; font-weight: 600; color: #64748B;">
                Panel Administrasi Sistem Informasi Penjualan Savonia.id
            </div>
            <div style="display: flex; align-items: center; gap: 16px;">
                <span style="background: #ECFDF5; color: #047857; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
                    ● Database MySQL: Aktif
                </span>
                <a href="{{ route('home') }}" class="btn btn-outline btn-sm">
                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                    <span>Tampilan Pelanggan</span>
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <main class="admin-content">
            @if(session('success'))
                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="check-circle" style="width: 18px; height: 18px; color: #10B981;"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="alert-circle" style="width: 18px; height: 18px; color: #EF4444;"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
