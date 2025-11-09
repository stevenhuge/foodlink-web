<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Foodlink</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --foodlink-primary: #2c5aa0;
            --foodlink-bg: #f8f9fa;
        }

        body {
            background-color: var(--foodlink-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            max-width: 450px; /* Lebar maksimum card */
            border-radius: .75rem; /* Sudut lebih bulat */
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); /* Bayangan yang soft */
            border: 0;
        }

        .brand-logo {
            color: var(--foodlink-primary);
            font-weight: 700;
            font-size: 1.75rem; /* Ukuran font brand */
        }

        /* Ubah warna tombol primary agar sesuai brand */
        .btn-primary {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
        }
        .btn-primary:hover {
            background-color: #244a85; /* Sedikit lebih gelap saat hover */
            border-color: #244a85;
        }
        .form-check-input:checked {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 p-3 p-md-0">

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 450px;">

                <!-- Card Login -->
                <div class="card login-card">
                    <div class="card-body p-4 p-md-5">

                        <!-- Brand/Logo -->
                        <div class="text-center mb-4">
                            <h2 class="brand-logo">
                                <i class="fas fa-utensils me-2"></i>Foodlink Admin
                            </h2>
                            <p class="text-muted mt-2">Silakan login untuk melanjutkan</p>
                        </div>

                        <!-- Tampilkan Error Validasi -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{-- Biasanya login error hanya satu pesan --}}
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Form Login -->
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <!-- Input Username (Floating Label) -->
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

                            <!-- Input Password (Floating Label) -->
                            <div class="form-floating mb-3">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Password"
                                       required>
                                <label for="password">Password</label>
                            </div>

                            <!-- Checkbox Remember Me -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Ingat saya (Remember me)
                                </label>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-sign-in-alt me-2"></i> Login
                                </button>
                            </div>
                        </form>

                    </div> <!-- End card-body -->
                </div> <!-- End card -->

            </div> <!-- End col -->
        </div> <!-- End row -->
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
