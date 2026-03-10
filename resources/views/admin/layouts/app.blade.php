<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Foodlink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --foodlink-primary: #4db43f;
            --foodlink-primary-hover: #3d9432;
            --foodlink-secondary: #f8fafc;
            --foodlink-accent: #ff6b35;
            --sidebar-bg: #ffffff;
            --sidebar-text: #475569;
            --sidebar-text-hover: #0f172a;
            --bg-body: #f1f5f9;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-body);
            color: #334155;
        }

        .sidebar {
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            min-height: 100vh;
            border-right: 1px solid #e2e8f0;
            z-index: 1020;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0.8rem;
            border-radius: 0.75rem;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar .nav-link:hover {
            color: var(--sidebar-text-hover);
            background: #f8fafc;
        }

        .sidebar .nav-link.active {
            color: var(--foodlink-primary);
            background: #f0fdf4;
            font-weight: 600;
        }

        .sidebar .nav-link i.icon-left {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 0.5rem;
            transition: color 0.25s ease;
        }

        .sidebar .nav-link:hover i.icon-left {
            color: var(--foodlink-primary);
        }

        .sidebar .nav-link.active i.icon-left {
            color: var(--foodlink-primary);
        }

        /* Styling untuk Submenu */
        .sidebar .submenu {
            background: transparent;
            margin-top: 0.25rem;
        }

        .sidebar .submenu .nav-link {
            padding-left: 3rem;
            font-size: 0.9rem;
            margin: 0.15rem 0.8rem;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid #f1f5f9;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .brand-logo {
            color: var(--foodlink-primary) !important;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .content-wrapper {
            background: var(--bg-body);
            min-height: 100vh;
        }

        /* Tweak Cards Global */
        .card {
            border-radius: 1rem;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .alert-custom-success { border-left: 4px solid var(--foodlink-primary); background-color: #f0fdf4; }
        .alert-custom-error { border-left: 4px solid #ef4444; background-color: #fef2f2; }

        .logout-btn { 
            transition: all 0.3s ease;
            border-radius: 2rem;
            font-weight: 500;
        }
        .logout-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2); }

        @media (max-width: 768px) {
            .sidebar { min-height: auto; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row g-0">
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-5 px-3">
                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                            <div class="brand-logo d-flex align-items-center justify-content-center mb-1 gap-2">
                                <img src="{{ asset('images/logo_foodlink_hijau_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 48px;">
                                <span>Foodlink</span>
                            </div>
                            <span class="badge bg-light text-secondary border mt-1">Admin Panel</span>
                        </a>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item px-3 mb-2">
                            <span class="text-xs fw-bold text-uppercase text-muted opacity-75" style="font-size: 0.7rem; letter-spacing: 0.5px;">Menu Utama</span>
                        </li>

                        {{-- Dashboard --}}
                        @if(auth()->guard('admin')->user() && in_array(auth()->guard('admin')->user()->role, ['Admin', 'SuperAdmin']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               href="{{ route('admin.dashboard') }}">
                                <div>
                                    <i class="bi bi-grid-1x2-fill icon-left"></i> Dashboard
                                </div>
                            </a>
                        </li>

                        {{-- === DROPDOWN MANAJEMEN PENGGUNA === --}}
                        <li class="nav-item">
                            <a class="nav-link collapsed"
                               data-bs-toggle="collapse"
                               href="#userManagementSubmenu"
                               role="button"
                               aria-expanded="{{ request()->routeIs('admin.mitra.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') ? 'true' : 'false' }}"
                               aria-controls="userManagementSubmenu">
                                <div>
                                    <i class="fas fa-users icon-left"></i> Data Pengguna
                                </div>
                                <i class="fas fa-chevron-down small"></i>
                            </a>

                            {{-- Isi Submenu --}}
                            <div class="collapse {{ request()->routeIs('admin.mitra.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.admins.*') ? 'show' : '' }}" id="userManagementSubmenu">
                                <ul class="nav flex-column submenu">

                                    {{-- 1. Manajemen Mitra --}}
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.mitra.*') ? 'active' : '' }}"
                                           href="{{ route('admin.mitra.index') }}">
                                            Manajemen Mitra
                                        </a>
                                    </li>

                                    {{-- 2. Manajemen User --}}
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                            href="{{ route('admin.users.index') }}">
                                            Manajemen User
                                        </a>
                                    </li>

                                    {{-- 3. Manajemen Admin (Hanya SuperAdmin) --}}
                                    @if(auth()->guard('admin')->user()->role === 'SuperAdmin')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                                           href="{{ route('admin.admins.index') }}">
                                            Manajemen Admin
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        {{-- === AKHIR DROPDOWN === --}}

                        <li class="nav-item px-3 mb-2 mt-4">
                            <span class="text-xs fw-bold text-uppercase text-muted opacity-75" style="font-size: 0.7rem; letter-spacing: 0.5px;">Manajemen Sistem</span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kategori-usaha.*') ? 'active' : '' }}"
                               href="{{ route('admin.kategori-usaha.index') }}">
                                <div>
                                    <i class="bi bi-tags-fill icon-left"></i> Kategori Usaha
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.penyanggahan.*') ? 'active' : '' }}"
                                href="{{ route('admin.penyanggahan.index') }}">
                                <div>
                                    <i class="fas fa-shield-alt icon-left"></i> Penyanggahan Mitra
                                </div>
                            </a>
                        </li>
                        @endif

                        {{-- SuperAdmin Only Links (Pemasukan & Alasan Blokir) --}}
                        @if(auth()->guard('admin')->user() && auth()->guard('admin')->user()->role === 'SuperAdmin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pemasukan.*') ? 'active' : '' }}"
                               href="{{ route('admin.pemasukan.index') }}">
                                <div>
                                    <i class="fas fa-chart-line icon-left"></i> Pemasukan
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}"
                               href="{{ route('admin.pengaturan.index') }}">
                                <div>
                                    <i class="fas fa-money-check-alt icon-left"></i> Pengaturan Pajak
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.alasan-blokir.*') ? 'active' : '' }}"
                               href="{{ route('admin.alasan-blokir.index') }}">
                                <div>
                                    <i class="fas fa-ban icon-left"></i> Alasan Blokir
                                </div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-md-9 col-lg-10 ms-sm-auto content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-custom">
                    <div class="container-fluid">
                        <button class="btn d-md-none border-0 text-muted" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                            <i class="bi bi-list fs-3"></i>
                        </button>

                        <div class="d-flex align-items-center ms-auto">
                            <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1 me-3 border">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                    <i class="fw-bold">{{ strtoupper(substr(Auth::guard('admin')->user()->nama_lengkap ?? 'A', 0, 1)) }}</i>
                                </div>
                                <span class="text-dark fw-medium d-none d-sm-block" style="font-size: 0.9rem;">
                                    {{ Auth::guard('admin')->user()->nama_lengkap ?? 'Admin' }}
                                    <br>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ Auth::guard('admin')->user()->role ?? 'Administrator' }}</small>
                                </span>
                            </div>

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-outline-danger btn-sm logout-btn px-3"
                                        onclick="return confirm('Anda yakin ingin logout?')">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>

                <main class="px-3 py-4">
                    <div class="container-fluid">
                        @if (session('success'))
                            <div class="alert alert-success alert-custom-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-custom-error alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.parentElement) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>
