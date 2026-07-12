<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | FoodLink</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --foodlink-primary: #ff6b6b;
            --foodlink-secondary: #ff8e53;
        }
        body {
            background-color: #fdfaf8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Outfit', sans-serif;
            overflow: hidden;
            margin: 0;
        }
        .error-container {
            text-align: center;
            padding: 3.5rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(255, 107, 107, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 550px;
            width: 90%;
            position: relative;
            z-index: 10;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--foodlink-primary), var(--foodlink-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0;
            line-height: 1.1;
            letter-spacing: -2px;
        }
        .error-icon {
            font-size: 4.5rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
            animation: floating 3.5s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(8deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .error-message {
            font-size: 1.75rem;
            color: #1e293b;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        .error-desc {
            color: #64748b;
            font-size: 1.05rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        .btn-back {
            background: linear-gradient(135deg, var(--foodlink-primary), var(--foodlink-secondary));
            border: none;
            color: white;
            padding: 0.9rem 2.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        .btn-back:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
            color: white;
        }
        .btn-back:active {
            transform: translateY(1px);
        }
        .food-decoration {
            position: absolute;
            font-size: 6rem;
            z-index: 1;
            color: var(--foodlink-primary);
            opacity: 0.05;
        }
        .dec-1 { top: 15%; left: 10%; transform: rotate(-25deg); }
        .dec-2 { top: 20%; right: 12%; transform: rotate(15deg); }
        .dec-3 { bottom: 18%; left: 15%; transform: rotate(-10deg); }
        .dec-4 { bottom: 12%; right: 10%; transform: rotate(25deg); }
        
        /* Floating background blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
        }
        .blob-1 {
            background-color: rgba(255, 107, 107, 0.4);
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
            animation: blobAnim 10s infinite alternate ease-in-out;
        }
        .blob-2 {
            background-color: rgba(255, 142, 83, 0.3);
            width: 500px;
            height: 500px;
            bottom: -150px;
            right: -100px;
            animation: blobAnim 12s infinite alternate-reverse ease-in-out;
        }
        @keyframes blobAnim {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.1) translate(30px, 30px); }
        }
        @media (max-width: 576px) {
            .error-code { font-size: 6rem; }
            .error-message { font-size: 1.4rem; }
            .food-decoration { font-size: 4rem; }
        }
    </style>
</head>
<body>

    <!-- Background Blobs -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <!-- Food Decorations -->
    <i class="fas fa-hamburger food-decoration dec-1"></i>
    <i class="fas fa-pizza-slice food-decoration dec-2"></i>
    <i class="fas fa-hotdog food-decoration dec-3"></i>
    <i class="fas fa-bowl-rice food-decoration dec-4"></i>

    <div class="error-container">
        <div class="error-icon">
            <i class="fa-solid fa-plate-wheat"></i>
        </div>
        <h1 class="error-code">404</h1>
        <h2 class="error-message">Oops! Menu Tidak Ditemukan</h2>
        <p class="error-desc">Halaman yang Anda cari sepertinya tidak ada di daftar menu kami, atau mungkin sudah dipindahkan.</p>
        
        <a href="{{ url('/') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Beranda
        </a>
    </div>

    <!-- Script fallback if needed -->
    <script>
        // Go back functionality if referrer is same origin
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.querySelector('.btn-back');
            if (document.referrer && document.referrer.includes(window.location.hostname)) {
                btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali Sebelumnya';
                btn.href = 'javascript:history.back()';
            }
        });
    </script>
</body>
</html>
