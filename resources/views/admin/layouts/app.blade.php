<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Foodlink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- <link href="{{ asset('css/custom-font.css') }}" rel="stylesheet"> --}}
    <style>
        :root {
            --foodlink-primary: #2c5aa0;
            --foodlink-secondary: #f8f9fa;
            --foodlink-accent: #ff6b35;
        }

        .sidebar {
            background: var(--foodlink-primary);
            color: white;
            min-height: 100vh;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            font-weight: 600;
        }

        .sidebar .nav-link i.icon-left {
            width: 20px;
            margin-right: 10px;
        }

        /* Styling untuk Submenu */
        .sidebar .submenu {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 2px;
        }

        .sidebar .submenu .nav-link {
            padding-left: 50px;
            font-size: 0.95rem;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-bottom: 1px solid #dee2e6;
        }

        .brand-logo {
            color: var(--foodlink-primary);
            font-weight: 700;
            font-size: 1.5rem;
        }

        .content-wrapper {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .alert-custom-success { border-left: 4px solid #28a745; }
        .alert-custom-error { border-left: 4px solid #dc3545; }

        .logout-btn { transition: all 0.3s ease; }
        .logout-btn:hover { transform: translateY(-1px); }

        @media (max-width: 768px) {
            .sidebar { min-height: auto; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="brand-logo" style="color: white;">
                            <i class="fas fa-utensils me-2"></i>Foodlink Admin
                        </h4>
                    </div>

                    <ul class="nav flex-column">

                        {{-- Dashboard --}}
                        @if(auth()->guard('admin')->user() && in_array(auth()->guard('admin')->user()->role, ['Admin', 'SuperAdmin']))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                               href="{{ route('admin.dashboard') }}">
                                <div>
                                    <i class="fas fa-tachometer-alt icon-left"></i> Dashboard
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

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kategori-usaha.*') ? 'active' : '' }}"
                               href="{{ route('admin.kategori-usaha.index') }}">
                                <div>
                                    <i class="fas fa-tags icon-left"></i> Kategori Usaha
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
                        <button class="btn d-md-none" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="d-flex align-items-center ms-auto">
                            <span class="me-3 text-muted d-none d-sm-block">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::guard('admin')->user()->nama_lengkap ?? 'Admin' }}
                            </span>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-outline-danger btn-sm logout-btn"
                                        onclick="return confirm('Anda yakin ingin logout?')">
                                    <i class="fas fa-sign-out-alt me-1"></i>
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
