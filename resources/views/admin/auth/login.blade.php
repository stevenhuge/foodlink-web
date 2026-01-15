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
            background-color: var(--foodlink-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            max-width: 450px;
            border-radius: .75rem;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1);
            border: 0;
        }

        .brand-logo {
            color: var(--foodlink-primary);
            font-weight: 700;
            font-size: 1.75rem;
        }

        /* --- MODIFIKASI TOMBOL --- */
        .btn-primary {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
            transition: all 0.3s ease; /* Transisi halus */
        }
        .btn-primary:hover {
            background-color: #3e8b33; 
            border-color: #3e8b33;
            box-shadow: 0 4px 12px rgba(77, 180, 63, 0.4); /* Efek glow pada tombol saat hover */
        }

        /* --- MODIFIKASI INPUT FIELD AGAR LEBIH HALUS (SOFT GLOW) --- */
        .form-control {
            border: 1px solid #ced4da;
            transition: all 0.3s ease-in-out; /* Animasi perubahan warna halus */
        }

        .form-control:focus {
            /* Warna border saat diklik */
            border-color: var(--foodlink-primary);
            
            /* RAHASIA GLOW HALUS: 
            0 0 15px = posisi tengah dengan blur 15px (sangat lembut)
            rgba(...) = warna hijau transparan (opacity 0.25) 
            */
            box-shadow: 0 0 15px rgba(77, 180, 63, 0.25); 
        }

        /* Modifikasi Checkbox agar seragam */
        .form-check-input:checked {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
        }
        .form-check-input:focus {
            box-shadow: 0 0 10px rgba(77, 180, 63, 0.25);
            border-color: var(--foodlink-primary);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3 p-md-0">

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 450px;">

                <div class="card login-card">
                    <div class="card-body p-4 p-md-5">

                        <div class="text-center mb-4">
                            <h2 class="brand-logo">
                                <i class="fas fa-utensils me-2"></i>Foodlink Admin
                            </h2>
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
</body>
</html>