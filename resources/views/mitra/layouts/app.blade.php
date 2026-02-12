<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mitra Dashboard') - Foodlink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom-font.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        :root {
            --sidebar-width: 260px;
            --foodlink-primary: #4DB43F; /* KEMBALI KE WARNA BIRU */
            --content-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--content-bg);
            overflow-x: hidden;
        }

        /* LAYOUT WRAPPER */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR (DESKTOP) */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--foodlink-primary); /* Gunakan warna Biru */
            color: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1030;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.2); /* Garis pemisah lebih halus */
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            flex-grow: 1;
            padding: 1rem;
            overflow-y: auto;
        }

        /* MENU ITEM STYLING (Disesuaikan untuk BG Biru) */
        .nav-link {
            color: rgba(255,255,255,0.8); /* Teks putih agak transparan */
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            font-weight: 500;
        }

        .nav-link i {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }

        /* Hover Effect: Putih Transparan */
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Active State: Putih Transparan Lebih Tebal */
        .nav-link.active {
            background-color: rgba(255,255,255,0.25);
            color: #fff;
            font-weight: 600;
        }

        /* Section Header (MANAJEMEN, KEUANGAN, dll) */
        .nav-section-header {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding-left: 1rem;
            letter-spacing: 0.5px;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        /* CONTENT AREA */
        .content-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAVBAR ATAS */
        .top-navbar {
            background-color: #fff;
            height: 70px;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        /* RESPONSIVE MOBILE */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1025;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="main-wrapper">

    <div class="mobile-overlay" id="mobileOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-utensils"></i>
                <span>FoodLink Mitra</span>
            </a>
        </div>

        <div class="sidebar-menu">
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a href="{{ route('mitra.dashboard') }}" class="nav-link {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>

                @auth('mitra')
                    @if (auth('mitra')->user()->status_verifikasi == "Verified")
                        <div class="nav-section-header">Manajemen Produk</div>

                        <li class="nav-item">
                            <a href="{{ route('mitra.produk.index') }}" class="nav-link {{ request()->routeIs('mitra.produk.*') ? 'active' : '' }}">
                                <i class="fas fa-box-open"></i> Produk Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mitra.pesanan.index') }}" class="nav-link {{ request()->routeIs('mitra.pesanan.*') ? 'active' : '' }}">
                                <i class="fas fa-receipt"></i> Pesanan Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mitra.barter.index') }}" class="nav-link {{ request()->routeIs('mitra.barter.*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt"></i> Barter Market
                            </a>
                        </li>

                        <div class="nav-section-header">Keuangan & Laporan</div>

                        <li class="nav-item">
                            <a href="{{ route('mitra.pemasukan.index') }}" class="nav-link {{ request()->routeIs('mitra.pemasukan.*') ? 'active' : '' }}">
                                <i class="fas fa-wallet"></i> Pemasukan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mitra.riwayat.index') }}" class="nav-link {{ request()->routeIs('mitra.riwayat.*') ? 'active' : '' }}">
                                <i class="fas fa-history"></i> Riwayat Transaksi
                            </a>
                        </li>
                        <div class="nav-section-header">Pengaturan Akun</div>

                        <li class="nav-item">
                            <a href="{{ route('mitra.profile.edit') }}" class="nav-link {{ request()->routeIs('mitra.profile.edit') ? 'active' : '' }}">
                                <i class="fas fa-user-cog"></i> Edit Profil
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('mitra.logout') }}">
                @csrf
                {{-- Tombol Logout dibuat transparan dengan border agar elegan di background biru --}}
                <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2 border-0 bg-white bg-opacity-10">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="content-wrapper">

        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light border d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>

                <h5 class="mb-0 d-none d-md-block fw-bold text-dark">
                    @yield('title', 'Dashboard')
                </h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                @auth('mitra')
                <div class="d-flex align-items-center gap-2">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark small">{{ auth('mitra')->user()->nama_mitra }}</div>
                        <div class="text-muted x-small" style="font-size: 11px;">{{ auth('mitra')->user()->email_bisnis }}</div>
                    </div>
                    {{-- Avatar dengan warna biru yang senada --}}
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; background-color: #2c5aa0 !important;">
                        {{ substr(auth('mitra')->user()->nama_mitra, 0, 1) }}
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <main class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Simple Sidebar Toggle Script
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('mobileOverlay');

    function toggleSidebar() {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    if(toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    }
</script>

@yield('scripts')
</body>
</html>
