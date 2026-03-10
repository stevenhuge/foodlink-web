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
            --foodlink-primary: #4DB43F; 
            --foodlink-primary-hover: #3d9231;
            --content-bg: #f8fbfa; /* Background lebih lapang & modern */
            --sidebar-bg: #ffffff; /* Clean Minimalist */
            --sidebar-color: #475569; /* Slate 600 */
            --sidebar-item-active: #f1f5f9; /* Slate 100 */
            --sidebar-item-active-text: #0f172a; /* Slate 900 */
        }

        body {
            /* Font profesional standard SaaS/E-Commerce */
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--content-bg);
            color: #334155; 
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Elevating Card Defaults to E-commerce grade */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); /* Soft premium shadow */
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        /* LAYOUT WRAPPER */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR (CLEAN MINIMALIST) */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--sidebar-color);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1030;
            border-right: 1px solid #e2e8f0; /* Garis pemisah abu-abu super halus */
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a; /* Logo color gelap kuat */
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        .sidebar-brand i {
            color: var(--foodlink-primary); /* Ikon logo warna brand */
        }

        .sidebar-menu {
            flex-grow: 1;
            padding: 1.25rem 1rem;
            overflow-y: auto;
        }

        /* MENU ITEM STYLING (Light Mode) */
        .nav-link {
            color: var(--sidebar-color);
            padding: 0.8rem 1rem;
            margin-bottom: 0.35rem;
            border-radius: 0.75rem; /* Lebih membulat */
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-link i {
            width: 26px;
            margin-right: 8px;
            text-align: center;
            font-size: 1.1rem;
            color: #94a3b8; /* Slate 400 - Icon warna soft */
            transition: color 0.2s ease;
        }

        /* Hover Effect: Soft Gray */
        .nav-link:hover {
            background-color: #f8fafc; /* Slate 50 */
            color: var(--sidebar-item-active-text);
        }

        .nav-link:hover i {
            color: var(--sidebar-item-active-text);
        }

        /* Active State: Tegas Minimalis */
        .nav-link.active {
            background-color: var(--sidebar-item-active);
            color: var(--sidebar-item-active-text);
            font-weight: 600;
            box-shadow: inset 3px 0 0 0 var(--foodlink-primary); /* Aksen sisi kiri */
        }

        .nav-link.active i {
            color: var(--foodlink-primary); /* Aksen warna ikon */
        }

        /* Section Header (MANAJEMEN, KEUANGAN, dll) */
        .nav-section-header {
            color: #94a3b8; /* Slate 400 */
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            padding-left: 1rem;
            letter-spacing: 0.8px;
        }

        .sidebar-footer {
            padding: 1.25rem 1rem;
            border-top: 1px solid #f1f5f9;
        }

        /* CONTENT AREA */
        .content-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* NAVBAR ATAS (Header Content) */
        .top-navbar {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            height: 76px;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        /* Tombol & Utilities Elegan */
        .btn-primary {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: var(--foodlink-primary-hover);
            border-color: var(--foodlink-primary-hover);
            box-shadow: 0 4px 12px rgba(77, 180, 63, 0.2);
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        /* Avatar Container Modern */
        .avatar-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 12px;
            border-radius: 50px;
            background: #fff;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .avatar-wrap:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* RESPONSIVE MOBILE */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.15); /* Shadow lebih kuat di mobile */
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
                background: rgba(15, 23, 42, 0.6); /* Backdrop lebih gelap estetik */
                backdrop-filter: blur(2px);
                z-index: 1025;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .mobile-overlay.show {
                display: block;
                opacity: 1;
            }
            
            .top-navbar {
                padding: 0 1rem;
            }
        }
    </style>
    @yield('styles')
    <link href="{{ asset('css/page-transitions.css') }}" rel="stylesheet">
</head>
<body>

<div class="main-wrapper">

    <div class="mobile-overlay" id="mobileOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo_foodlink_hijau_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 32px;">
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
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Keluar
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
                <div class="avatar-wrap">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark small" style="line-height: 1.2;">{{ auth('mitra')->user()->nama_mitra }}</div>
                        <div class="text-muted" style="font-size: 11px;">Mulai berjualan</div>
                    </div>
                    {{-- Avatar Modern --}}
                    @if(auth('mitra')->user()->logo_mitra)
                        <img src="{{ asset(auth('mitra')->user()->logo_mitra) }}" alt="Avatar" class="rounded-circle object-fit-cover shadow-sm" style="width: 36px; height: 36px;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm text-white" style="width: 36px; height: 36px; background-color: var(--foodlink-primary);">
                            {{ substr(auth('mitra')->user()->nama_mitra, 0, 1) }}
                        </div>
                    @endif
                </div>
                @endauth
            </div>
        </header>

        <main class="p-4" id="main-container">
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
        if (sidebar.classList.contains('show')) {
            overlay.style.display = 'block';
            setTimeout(() => overlay.classList.add('show'), 10);
        } else {
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 300);
        }
    }

    if(toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    }
</script>

<script src="{{ asset('js/page-transitions.js') }}"></script>

@yield('scripts')
</body>
</html>
