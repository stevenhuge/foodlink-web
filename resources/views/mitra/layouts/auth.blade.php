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
            --foodlink-primary: #2c5aa0;
            --bg-color: #eef2f7;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 420px; /* Lebar maksimal kotak login */
            padding: 15px;
        }

        .auth-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            border: none;
        }

        .auth-header {
            background-color: #fff;
            padding: 2rem 1rem 1rem 1rem;
            text-align: center;
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--foodlink-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .auth-body {
            padding: 2rem;
        }

        /* Input Styling */
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: var(--foodlink-primary);
        }

        .form-control:focus {
            border-color: var(--foodlink-primary);
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.15);
        }

        .btn-primary {
            background-color: var(--foodlink-primary);
            border-color: var(--foodlink-primary);
            padding: 0.7rem;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #234b8c;
            border-color: #234b8c;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <a href="/" class="brand-logo">
                    <i class="fas fa-utensils"></i> FoodLink
                </a>
                <p class="text-muted mt-2 mb-0 small">Portal Khusus Mitra</p>
            </div>

            <div class="auth-body">
                @yield('content')
            </div>
        </div>

        <div class="text-center mt-4 text-muted small">
            &copy; {{ date('Y') }} FoodLink Indonesia
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
