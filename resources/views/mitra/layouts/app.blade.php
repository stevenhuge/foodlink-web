<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mitra Dashboard') - Foodlink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJc5fS5Q/O8YVlB+t7j6K/s5P2S7q8o2J+1P5V7l2gL1A0O4/kQ4/h5P+5j+4Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* Gaya Kustom untuk Tampilan Lebih Bersih */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,.04); }
        .foodlink-brand { font-size: 1.25rem; font-weight: 700; color: #007bff !important; }
        .btn-logout { background-color: #dc3545; border-color: #dc3545; }
        .btn-logout:hover { background-color: #c82333; border-color: #c82333; }
        /* Mengganti styling form dengan kelas Bootstrap */
        .form-control, .form-select { border-radius: .25rem; }
        /* Menghapus gaya tabel bawaan karena sudah ditangani Bootstrap */
        /* table, th, td, input, button styles are mostly handled by Bootstrap now */
    </style>

    <style>
        /* Tetap pertahankan gaya form untuk konsistensi di mana Bootstrap mungkin tidak sepenuhnya menggantikan */
        input[type=text], input[type=email], input[type=password], input[type=number], input[type=file], input[type=datetime-local], select, textarea {
            width: 100%; padding: 8px; margin-bottom: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container-fluid px-4 px-lg-5">
            {{-- Link ke Dashboard HANYA jika sudah login --}}
            @auth('mitra')
                <a class="navbar-brand foodlink-brand" href="{{ route('mitra.dashboard') }}">
                    <i class="fas fa-home me-2"></i>Foodlink Mitra Panel
                </a>
            @else
                <span class="navbar-brand foodlink-brand">
                    <i class="fas fa-store me-2"></i>Foodlink Mitra Panel
                </span>
            @endauth

            <div class="d-flex align-items-center">
                {{-- Tampilkan info user & tombol logout JIKA sudah login --}}
                @auth('mitra')
                    <span class="navbar-text me-3 d-none d-sm-inline">
                        Halo, <b>{{ auth('mitra')->user()->nama_mitra }}</b>
                    </span>

                    <ul class="navbar-nav flex-row">
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('mitra.profile.edit') }}">
                                <i class="fas fa-user-edit me-1"></i> Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            {{-- FORM LOGOUT --}}
                            <form method="POST" action="{{ route('mitra.logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-logout" onclick="return confirm('Anda yakin ingin logout?')">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                @else
                    {{-- Tampilkan link Login/Register JIKA BELUM login --}}
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

    <div class="container mt-4 px-4 px-lg-5">
        {{-- Tampilkan Pesan Sukses/Error (Menggunakan Alerts Bootstrap) --}}
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
        {{-- Tampilkan Error Validasi (Penting untuk Form) --}}
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

        {{-- Konten Halaman --}}
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
