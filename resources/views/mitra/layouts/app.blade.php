<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mitra Dashboard') - Foodlink</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJc5fS5Q/O8YVlB+t7j6K/s5P2S7q8o2J+1P5V7l2gL1A0O4/kQ4/h5P+5j+4Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Gaya Kustom untuk Tampilan Baru */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6; /* Latar belakang body sedikit lebih cerah */
            /* display: flex; */ /* Dihapus */
            /* flex-direction: column; */ /* Dihapus */
            /* min-height: 100vh; */ /* Dihapus */
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.04);
            background-color: #ffffff;
            height: 56px;
            /* flex-shrink: 0; */ /* Dihapus */
        }
        .foodlink-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #007bff !important;
        }
        /* Navbar user name */
        .navbar-text b {
            color: #343a40;
            font-weight: 600;
        }

        /* --- STYLING LAYOUT BARU --- */

        /* Wrapper utama untuk sidebar + konten */
        .main-wrapper {
            display: flex;
            flex: 1;
            /* overflow: hidden; */ /* Dihapus */
            height: 100vh; /* Diubah: Mengisi tinggi viewport */
        }

        /* Sidebar Permanen (Desktop) */
        .sidebar-permanent {
            width: 260px;
            flex-shrink: 0;
            background-color: #2c3e50; /* Warna dark-blue-grey */
            height: 100vh; /* Diubah: Tinggi penuh */
            /* position: sticky; */ /* Dihapus */
            /* top: 56px; */ /* Dihapus */
            overflow-y: auto;
            box-shadow: inset -3px 0px 5px -2px rgba(0,0,0,0.1); /* Shadow ke dalam */
        }

        /* Konten Halaman Utama */
        .page-content-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            /* padding: 1.5rem; */ /* Dihapus: Padding dipindahkan ke container-fluid */
            background-color: #f4f7f6; /* Cocokkan dengan body */
        }

        .page-content-wrapper .container-fluid {
            padding: 0;
            margin-top: 0;
        }

        /* --- STYLING MENU SIDEBAR (Berlaku untuk Keduanya) --- */

        /* Offcanvas (Mobile) */
        .offcanvas-start {
            background-color: #2c3e50; /* Cocokkan dengan sidebar permanen */
        }
        .offcanvas-header {
            border-bottom: 1px solid #3b5068; /* Garis pemisah soft */
        }
        .offcanvas-title {
            color: #ffffff !important; /* Judul putih */
        }

        /* Menu Links */
        .sidebar-menu {
            padding-top: 0.5rem; /* Padding di atas list menu */
        }
        .sidebar-menu .nav-link {
            color: #bdc3c7; /* Warna teks abu-abu muda (soft) */
            padding: 0.85rem 1.25rem; /* Padding lebih besar */
            border-radius: .375rem;
            transition: all 0.2s ease-in-out;
            font-weight: 500; /* Sedikit lebih tebal */
            margin-bottom: 0.25rem; /* Jarak antar item */
        }
        .sidebar-menu .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.05); /* Efek hover transparan */
            color: #ffffff; /* Teks jadi putih */
            transform: translateX(3px); /* Efek geser kecil */
        }
        .sidebar-menu .nav-link.active {
            background-color: #007bff; /* Biru cerah */
            color: #fff;
            font-weight: 600; /* Lebih tebal saat aktif */
            box-shadow: 0 4px 8px -2px rgba(0,123,255,0.3); /* Bayangan untuk link aktif */
        }
        .sidebar-menu .nav-link .fa-fw {
            width: 1.25em;
            margin-right: 0.5rem; /* Jarak ikon ke teks lebih besar */
        }

        /* Pemisah HR */
        .sidebar-menu hr {
            border-top: 1px solid #3b5068;
            margin: 1rem 0.5rem;
        }

        /* Form Logout */
        .sidebar-logout-form {
            padding: 0 0.5rem; /* Samakan padding dgn menu */
        }
        .sidebar-logout-form .btn-logout {
            width: 100%;
            background-color: #e74c3c; /* Merah yang lebih soft */
            border-color: #e74c3c;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        .sidebar-logout-form .btn-logout:hover {
            background-color: #c0392b; /* Merah lebih gelap saat hover */
            border-color: #c0392b;
        }

    </style>
</head>
<body>

    <!-- Navigasi Atas (Navbar) - DIPINDAHKAN KE DALAM .page-content-wrapper -->
    <!-- <nav class="navbar ...">...</nav> -->

    <!-- Wrapper Utama: Sidebar + Konten -->
    <div class="main-wrapper">

        @auth('mitra')
        <!-- Sidebar (Offcanvas) - HANYA untuk mobile/tablet (d-lg-none) -->
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mitraSidebarOffcanvas" aria-labelledby="mitraSidebarOffcanvasLabel">
            <div class="offcanvas-header text-white">
                <h5 class="offcanvas-title foodlink-brand" id="mitraSidebarOffcanvasLabel">
                    <i class="fas fa-store me-2"></i> Mitra Panel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column p-3 sidebar-menu">
                <!-- Menu Navigasi Utama (Mobile) -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('mitra.dashboard') }}" class="nav-link {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mitra.profile.edit') }}" class="nav-link {{ request()->routeIs('mitra.profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-user-edit"></i>
                            Edit Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-fw fa-box-open"></i>
                            Produk Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-fw fa-receipt"></i>
                            Pesanan
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="sidebar-logout-form">
                    <form method="POST" action="{{ route('mitra.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-logout text-white">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar (Permanent) - HANYA untuk desktop (d-none d-lg-block) -->
        <nav class="sidebar-permanent d-none d-lg-block">
            <div class="d-flex flex-column p-3 sidebar-menu">
                <!-- Menu Navigasi Utama (Desktop) -->
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('mitra.dashboard') }}" class="nav-link {{ request()->routeIs('mitra.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mitra.profile.edit') }}" class="nav-link {{ request()->routeIs('mitra.profile.edit') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-user-edit"></i>
                            Edit Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mitra.produk.index') }}" class="nav-link">
                            <i class="fas fa-fw fa-box-open"></i>
                            Produk Saya
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mitra.pesanan.index') }}" class="nav-link">
                            <i class="fas fa-fw fa-receipt"></i>
                            Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mitra.riwayat.index') }}" class="nav-link">
                            <i class="fas fa-fw fa-history"></i>
                            Riwayat Transaksi
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="sidebar-logout-form">
                    <form method="POST" action="{{ route('mitra.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-logout text-white">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        @endauth

        <!-- Konten Halaman Utama -->
        <div class="page-content-wrapper">

            <!-- Navigasi Atas (Navbar) - DIPINDAHKAN KE SINI -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
                <div class="container-fluid px-4 px-lg-5">

                    @auth('mitra')
                        <!-- Tombol Hamburger (HANYA tampil di mobile/tablet) -->
                        <button class="btn btn-outline-secondary me-2 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mitraSidebarOffcanvas" aria-controls="mitraSidebarOffcanvas">
                            <i class="fas fa-bars"></i>
                        </button>
                    @endauth

                    <!-- Brand/Logo -->
                    <a class="navbar-brand foodlink-brand" href="{{ auth('mitra')->check() ? route('mitra.dashboard') : '#' }}">
                        @auth('mitra')
                            <i class="fas fa-store me-2"></i>
                        @else
                            <i class="fas fa-store me-2"></i>
                        @endauth
                        Foodlink Mitra
                    </a>

                    <!-- Konten Navbar Kanan -->
                    <div class="d-flex align-items-center ms-auto">
                        @auth('mitra')
                            <!-- Info User (HANYA untuk user login) -->
                            <span class="navbar-text me-3 d-none d-sm-inline">
                                Halo, <b>{{ auth('mitra')->user()->nama_mitra }}</b>
                            </span>
                        @else
                            <!-- Tombol Login/Register (HANYA untuk tamu) -->
                            <a class="btn btn-outline-primary me-2" href="{{ route('mitra.login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                            <a class="btn btn-primary" href="{{ route('mitra.register') }}">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>
            <!-- AKHIR DARI NAVBAR YANG DIPINDAH -->


            <!-- Pindahkan container-fluid ke sini agar padding-nya konsisten -->
            <div class="container-fluid p-4"> <!-- Diubah: Ditambahkan padding 'p-4' -->

                <!-- Tampilkan Pesan Sukses/Error -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tampilkan Error Validasi -->
                @if ($errors->any())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle me-2"></i><strong>Oops! Ada kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Konten Halaman Dinamis -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div> <!-- End .page-content-wrapper -->

    </div> <!-- End .main-wrapper -->

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
