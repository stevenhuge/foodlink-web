<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login Mitra') - Foodlink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        :root {
            --foodlink-primary: #4db43f;
            --foodlink-primary-dark: #3e8b33;
            --bg-color: #ffffff;
            --text-color: #1e293b;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-color);
            min-height: 100vh;
            margin: 0;
            display: flex;
        }

        .auth-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* LEFT SIDEBAR (Merek & Ilustrasi) */
        .auth-sidebar {
            flex: 1;
            background: linear-gradient(135deg, var(--foodlink-primary) 0%, var(--foodlink-primary-dark) 100%);
            color: white;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        /* Pola Abstract Overlay */
        .auth-sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.15) 2px, transparent 2px);
            background-size: 30px 30px;
            opacity: 0.8;
            z-index: 0;
        }
        
        /* Lingkaran Dekoratif */
        .circle-decor {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            z-index: 0;
        }
        .circle-1 { width: 400px; height: 400px; top: -100px; right: -100px; }
        .circle-2 { width: 300px; height: 300px; bottom: -50px; left: -50px; }

        .sidebar-content {
            position: relative;
            z-index: 1;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            padding: 2.5rem;
            margin-top: auto;
            margin-bottom: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* RIGHT MENU (Form) */
        .auth-main {
            flex: 1;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            padding: 3rem;
            background-color: #ffffff;
            box-shadow: -10px 0 30px rgba(0,0,0,0.03);
            z-index: 2;
            overflow-y: auto;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            margin: auto; /* Memusatkan secara vertikal & horizontal di kolom kanan */
        }

        .auth-header {
            margin-bottom: 2.5rem;
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--foodlink-primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        /* Input Styling */
        .form-floating > .form-control {
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        .form-floating > .form-control:focus {
            border-color: var(--foodlink-primary);
            box-shadow: 0 0 0 0.25rem rgba(77, 180, 63, 0.15);
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--foodlink-primary);
        }

        .btn-primary {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
            border-radius: 0.75rem;
            padding: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--foodlink-primary-dark);
            border-color: var(--foodlink-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(77, 180, 63, 0.25);
        }

        /* Sembunyikan sidebar text untuk layar HP (Mobile Responsive) */
        @media (max-width: 991.98px) {
            .auth-sidebar {
                display: none !important;
            }
            .auth-main {
                max-width: 100%;
                background-color: #f8fbf9; /* Latar sedikit hijau pastel di HP */
            }
            .auth-container {
                background: white;
                padding: 2.5rem;
                border-radius: 1.5rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            }
        }
    </style>
    <link href="{{ asset('css/page-transitions.css') }}" rel="stylesheet">
</head>
<body>

    <div class="auth-wrapper">
        <!-- LEFT SIDEBAR (Hanya tampil di Desktop) -->
        <div class="auth-sidebar d-none d-lg-flex">
            <div class="circle-decor circle-1"></div>
            <div class="circle-decor circle-2"></div>
            
            <div class="sidebar-content">
                <a href="/" class="text-white text-decoration-none d-inline-flex align-items-center gap-3 mb-5 hover-opacity">
                    <img src="{{ asset('images/logo_foodlink_putih_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 48px;">
                    <span class="fs-3 fw-bold tracking-tight">FoodLink</span>
                </a>
            </div>

            <div class="sidebar-content glass-panel">
                <div class="mb-4">
                    <span class="badge bg-white text-success rounded-pill px-3 py-2 fw-semibold">Ekonomi Sirkular B2B</span>
                </div>
                <h2 class="display-6 fw-bold mb-4">Ubah Limbah Jadi Peluang Baru</h2>
                <p class="fs-5 opacity-75 mb-0">
                    Bergabung bersama ribuan mitra sukses lainnya mengurangi food waste dengan Jual-Cepat, Donasi, dan sistem Barter tanpa uang tunai.
                </p>
                
                <div class="d-flex gap-3 mt-5">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle fs-5"></i>
                        <span class="small fw-medium">Hemat Biaya</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle fs-5"></i>
                        <span class="small fw-medium">Relasi B2B Kuat</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle fs-5"></i>
                        <span class="small fw-medium">Ramah Lingkungan</span>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-content mt-5 opacity-75 small">
                &copy; {{ date('Y') }} FoodLink - Mewujudkan Bisnis F&B Berkelanjutan.
            </div>
        </div>

        <!-- RIGHT AREA (Form Autentikasi) -->
        <div class="auth-main">
            <div class="auth-container">
                
                <!-- Logo untuk Mobile -->
                <div class="d-lg-none text-center mb-4">
                    <a href="/" class="brand-logo mb-2">
                        <img src="{{ asset('images/logo_foodlink_hijau_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 55px;">
                        <span>FoodLink</span>
                    </a>
                </div>

                <div class="auth-header text-start d-none d-lg-block">
                    <h1 class="h2 fw-bold mb-2">Portal Mitra</h1>
                    <p class="text-muted">Masuk ke akun usaha Anda untuk mulai mengelola stok hari ini.</p>
                </div>

                <div class="auth-body p-0" id="auth-form-container">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global Layout Page Transitions -->
    <script src="{{ asset('js/page-transitions.js') }}"></script>
</body>
</html>
