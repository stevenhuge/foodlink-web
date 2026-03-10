<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Foodlink</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --foodlink-primary: #4db43f;
            --foodlink-bg: #f8f9fa;
        }

        body {
            /* Animated Mesh Gradient */
            background: linear-gradient(-45deg, #e9f5e9, #d1ecd1, #e3f2fd, #fcfaf0);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Abstract Floating Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: -1;
            opacity: 0.5;
            animation: float 10s infinite alternate ease-in-out;
        }
        .shape-1 {
            width: 50vw; height: 50vw;
            background: #4db43f;
            top: -20vh; left: -20vw;
        }
        .shape-2 {
            width: 40vw; height: 40vw;
            background: #fbbf24;
            bottom: -15vh; right: -15vw;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -50px) scale(1.1); }
        }

        .login-card {
            max-width: 440px;
            /* Glassmorphism */
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05), inset 0 0 0 1px rgba(255,255,255,0.4);
            overflow: hidden;
            margin: auto;
        }

        .brand-logo {
            color: var(--foodlink-primary);
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        /* --- MODIFIKASI TOMBOL --- */
        .btn-primary {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
            border-radius: 0.75rem;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #3e8b33; 
            border-color: #3e8b33;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(77, 180, 63, 0.3);
        }

        /* --- MODIFIKASI INPUT FIELD --- */
        .form-floating > .form-control {
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.1);
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--foodlink-primary);
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(77, 180, 63, 0.15);
        }

        .form-check-input {
            border-radius: 4px;
        }
        .form-check-input:checked {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(77, 180, 63, 0.15);
            border-color: var(--foodlink-primary);
        }
    </style>
    <link href="{{ asset('css/page-transitions.css') }}" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3 p-md-0">
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12 w-100 d-flex justify-content-center">

                <div class="card login-card w-100">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-5">
                            <div class="brand-logo d-flex align-items-center justify-content-center mb-2 gap-2">
                                <img src="{{ asset('images/logo_foodlink_hijau_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 60px;">
                                <span>Foodlink Admin</span>
                            </div>
                            <p class="text-muted mt-2">Silakan login untuk melanjutkan</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text" 
                                    class="form-control @error('username') is-invalid @enderror" 
                                    id="username" 
                                    name="username" 
                                    value="{{ old('username') }}" 
                                    placeholder="Username" 
                                    required 
                                    autofocus>
                                <label for="username">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Password" 
                                    required>
                                <label for="password">Password</label>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Ingat saya (Remember me)
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login
                                </button>
                            </div>
                        </form>

                    </div> </div> </div> </div> </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/page-transitions.js') }}"></script>
</body>
</html>