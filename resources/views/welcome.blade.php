<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FoodLink - Save Your Food, Save The Earth</title>
    <meta name="description" content="FoodLink adalah ekosistem ekonomi sirkular yang menghubungkan usaha makanan dengan masyarakat untuk mengurangi food waste. Dapatkan makanan berkualitas dengan harga terjangkau."/>
    <meta name="keywords" content="FoodLink, Food Waste, Makanan Sisa, Ekonomi Sirkular, Diskon Makanan, Makanan Murah, Penyelamatan Makanan" />
    <meta name="author" content="FoodLink Team" />
    <meta property="og:title" content="FoodLink - Save Your Food, Save The Earth" />
    <meta property="og:description" content="Bergabunglah dengan ekosistem ekonomi sirkular FoodLink. Kurangi food waste dan dapatkan makanan berkualitas dengan harga terjangkau." />
    <meta property="og:image" content="{{ asset('images/logo_foodlink_background_hijau.png') }}" />
    <meta property="og:type" content="website" />
    <meta name="google-site-verification" content="vGV17OD3mjn-q8y_urR_L2kLhJDAnE0TRcJhQ0OWcpc" />
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon-192x192-rounded.png') }}" sizes="192x192" type="image/png">
    
    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- LIBRARIES UNTUK ANIMASI -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- END LIBRARIES -->

    <style>
        :root {
            --fl-green: #4DB43F;
            --fl-green-dark: #3aa233;
            --fl-bg: #f5fbf6;
            --fl-muted: #94a3a1;

            /* ---- Motion tokens (tidak mengubah warna, hanya ritme animasi) ---- */
            --fl-ease: cubic-bezier(0.16, 1, 0.3, 1);      /* expo-out: halus & cinematic */
            --fl-ease-soft: cubic-bezier(0.22, 0.61, 0.36, 1);
            --fl-ease-back: cubic-bezier(0.34, 1.4, 0.5, 1); /* sedikit overshoot */
            --fl-dur: 0.45s;
            --fl-dur-fast: 0.22s;
            --fl-dur-slow: 0.7s;
        }

        /* ============ GLOBAL & TIPOGRAFI ============ */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 96px; /* offset navbar sticky saat lompat ke anchor */
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--fl-bg);
            color: #0f172a;
            line-height: 1.65;
            letter-spacing: -0.011em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            overflow-x: clip; /* buang scroll horizontal dari badge-float; aman untuk sticky */
        }

        h1, h2, h3, h4, h5, h6 { letter-spacing: -0.025em; }
        .display-4, .display-6 { letter-spacing: -0.035em; }
        .lead { line-height: 1.7; letter-spacing: -0.006em; }
        p { letter-spacing: -0.005em; }
        small, .small { letter-spacing: 0; }

        ::selection { background-color: rgba(77, 180, 63, 0.22); color: #0f172a; }

        :focus-visible {
            outline: 2px solid var(--fl-green);
            outline-offset: 3px;
        }

        img { -webkit-user-drag: none; }

        /* ============ AOS: jarak travel lebih ringan + timing lebih halus ============ */
        [data-aos="fade-up"]    { transform: translate3d(0, 32px, 0); }
        [data-aos="fade-down"]  { transform: translate3d(0, -32px, 0); }
        [data-aos="fade-left"]  { transform: translate3d(44px, 0, 0); }
        [data-aos="fade-right"] { transform: translate3d(-44px, 0, 0); }
        [data-aos="zoom-in"]    { transform: scale(0.965); }

        /* Blok besar tidak boleh memantul: pakai kurva expo-out */
        [data-aos="zoom-in"][data-aos="zoom-in"],
        [data-aos="fade-left"][data-aos="fade-left"],
        [data-aos="fade-right"][data-aos="fade-right"] {
            transition-timing-function: var(--fl-ease);
        }

        .text-fl-green { color: var(--fl-green) !important; }
        .bg-fl-green { background-color: var(--fl-green) !important; }
        .bg-fl-green-subtle { background-color: rgba(77, 180, 63, 0.1) !important; }

        /* ============ BUTTONS: mikro-interaksi ============ */
        .btn-fl-primary {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-color: var(--fl-green);
            color: white;
            border: none;
            padding: 0.75rem 1.35rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            border-radius: 0.65rem;
            box-shadow: 0 6px 16px -8px rgba(77, 180, 63, 0.55);
            transition: transform var(--fl-dur) var(--fl-ease-back),
                        box-shadow var(--fl-dur) var(--fl-ease),
                        background-color var(--fl-dur-fast) linear;
        }
        /* kilau yang menyapu saat hover */
        .btn-fl-primary::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(115deg, transparent 28%, rgba(255,255,255,0.26) 47%, transparent 66%);
            transform: translateX(-130%);
            transition: transform var(--fl-dur-slow) var(--fl-ease);
        }
        .btn-fl-primary:hover::after,
        .btn-fl-primary:focus-visible::after { transform: translateX(130%); }

        .btn-fl-primary:hover,
        .btn-fl-primary:focus-visible {
            background-color: var(--fl-green-dark);
            color: white;
            transform: translateY(-3px) scale(1.015);
            box-shadow: 0 16px 32px -12px rgba(77, 180, 63, 0.7),
                        0 4px 12px -6px rgba(6, 20, 10, 0.22) !important;
        }
        .btn-fl-primary:active {
            transform: translateY(-1px) scale(0.982);
            transition-duration: 0.09s;
            box-shadow: 0 6px 14px -9px rgba(77, 180, 63, 0.6) !important;
        }
        .btn-fl-primary svg { transition: transform var(--fl-dur) var(--fl-ease-back); }
        .btn-fl-primary:hover svg { transform: translateX(3px); }

        .btn-fl-outline {
            position: relative;
            isolation: isolate;
            background-color: transparent;
            border: 1px solid var(--fl-green);
            color: var(--fl-green);
            padding: 0.75rem 1.35rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            border-radius: 0.65rem;
            transition: color var(--fl-dur-fast) var(--fl-ease-soft),
                        transform var(--fl-dur) var(--fl-ease-back),
                        box-shadow var(--fl-dur) var(--fl-ease);
        }
        /* latar hijau yang menyebar dari tengah, bukan berganti seketika */
        .btn-fl-outline::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            border-radius: inherit;
            background-color: var(--fl-green);
            transform: scale(0.25);
            opacity: 0;
            transition: transform var(--fl-dur) var(--fl-ease),
                        opacity var(--fl-dur-fast) linear;
        }
        .btn-fl-outline:hover::before,
        .btn-fl-outline:focus-visible::before { transform: scale(1); opacity: 1; }

        .btn-fl-outline:hover,
        .btn-fl-outline:focus-visible {
            background-color: transparent;
            color: white;
            transform: translateY(-3px) scale(1.015);
            box-shadow: 0 16px 32px -14px rgba(77, 180, 63, 0.65) !important;
        }
        .btn-fl-outline:active {
            transform: translateY(-1px) scale(0.982);
            transition-duration: 0.09s;
        }

        /* Tombol Play Store (warna asli dipertahankan, hanya gerak) */
        .btn-success {
            transition: transform var(--fl-dur) var(--fl-ease-back),
                        box-shadow var(--fl-dur) var(--fl-ease),
                        filter var(--fl-dur-fast) linear;
        }
        .btn-success:hover {
            transform: translateY(-3px) scale(1.015);
            box-shadow: 0 16px 30px -14px rgba(6, 20, 10, 0.45) !important;
        }
        .btn-success:active { transform: translateY(-1px) scale(0.985); transition-duration: 0.09s; }
        .btn-success svg { transition: transform var(--fl-dur) var(--fl-ease-back); }
        .btn-success:hover svg { transform: translateY(2px); }

        .shadow-fl-card {
            box-shadow: 0 10px 30px rgba(6,20,10,0.08);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .shadow-fl-soft {
            box-shadow: 0 18px 40px rgba(21,66,30,0.12);
        }
        .rounded-4 { border-radius: 1rem !important; }
        .rounded-5 { border-radius: 1.5rem !important; }
        .rounded-xl-3 { border-radius: 28px !important; }

        /* ============ CARDS: umpan balik hover yang elegan ============ */
        .card {
            transition: transform var(--fl-dur-slow) var(--fl-ease),
                        box-shadow var(--fl-dur-slow) var(--fl-ease);
            will-change: transform;
        }
        #fitur .card:hover,
        #cara .card:hover,
        #manfaat .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 55px -24px rgba(6, 20, 10, 0.3),
                        0 12px 26px -16px rgba(77, 180, 63, 0.3);
        }

        /* ikon di dalam kartu ikut hidup */
        #fitur .card .rounded-3,
        #manfaat .card .rounded-3,
        #cara .card .rounded-circle,
        #fitur .bg-white.rounded-4.border .rounded-3 {
            transition: transform var(--fl-dur) var(--fl-ease-back),
                        box-shadow var(--fl-dur) var(--fl-ease),
                        background-color var(--fl-dur) linear;
        }
        #fitur .card:hover .rounded-3,
        #manfaat .card:hover .rounded-3,
        #fitur .bg-white.rounded-4.border:hover .rounded-3 {
            transform: scale(1.1) rotate(-5deg);
            box-shadow: 0 12px 22px -10px rgba(6, 20, 10, 0.38);
        }
        #cara .card:hover .rounded-circle {
            transform: scale(1.14);
            box-shadow: 0 12px 22px -10px rgba(77, 180, 63, 0.6);
        }
        /* hijau yang sama, hanya sedikit lebih pekat saat hover */
        #fitur .card:hover .bg-fl-green-subtle,
        #manfaat .card:hover .bg-fl-green-subtle,
        #fitur .bg-white.rounded-4.border:hover .bg-fl-green-subtle {
            background-color: rgba(77, 180, 63, 0.18) !important;
        }

        /* kartu kecil "Efisiensi Bisnis / Keamanan / Real-time" */
        #fitur .bg-white.rounded-4.border {
            transition: transform var(--fl-dur-slow) var(--fl-ease),
                        box-shadow var(--fl-dur-slow) var(--fl-ease),
                        border-color var(--fl-dur) linear;
        }
        #fitur .bg-white.rounded-4.border:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 40px -22px rgba(6, 20, 10, 0.26);
            border-color: rgba(77, 180, 63, 0.35) !important;
        }

        /* ============ PHONE MOCKUP: glow + pantulan cahaya ============ */
        .phone-mockup {
            width: 320px;
            height: 640px;
            border-radius: 48px;
            background: linear-gradient(to bottom, #4DB43F, #49c85a);
            border: 8px solid #0b0b0b;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 28px 60px -26px rgba(6, 20, 10, 0.45),
                        0 18px 55px -22px rgba(77, 180, 63, 0.45);
            margin: 0 auto;
            transition: transform var(--fl-dur-slow) var(--fl-ease),
                        box-shadow var(--fl-dur-slow) var(--fl-ease);
        }
        .phone-mockup:hover {
            transform: translateY(-10px);
            box-shadow: 0 44px 80px -30px rgba(6, 20, 10, 0.5),
                        0 28px 72px -24px rgba(77, 180, 63, 0.6);
        }
        .phone-screen {
            position: absolute;
            inset: 0;
            border-radius: 36px;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }
        /* pantulan cahaya statis di kaca layar */
        .phone-screen::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 2;
            pointer-events: none;
            background:
                radial-gradient(120% 75% at 18% 0%, rgba(255,255,255,0.28) 0%, rgba(255,255,255,0.05) 44%, transparent 70%),
                linear-gradient(165deg, rgba(255,255,255,0.10) 0%, transparent 38%);
        }
        /* kilau yang menyapu layar secara berkala */
        .phone-screen::before {
            content: "";
            position: absolute;
            top: -60%;
            left: -45%;
            width: 45%;
            height: 220%;
            z-index: 3;
            pointer-events: none;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.34), transparent);
            transform: rotate(18deg) translateX(-180%);
            animation: flScreenSheen 7s var(--fl-ease-soft) infinite;
        }
        @keyframes flScreenSheen {
            0%, 64%  { transform: rotate(18deg) translateX(-180%); opacity: 0; }
            70%      { opacity: 0.9; }
            100%     { transform: rotate(18deg) translateX(300%); opacity: 0; }
        }

        /* ============ BADGE FLOAT: bobbing dinamis ============ */
        .badge-float {
            position: absolute;
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 14px 28px -12px rgba(0, 0, 0, 0.22);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 20;
            max-width: 200px;
            animation: flBob 5.4s ease-in-out infinite;
            will-change: transform;
            transition: box-shadow var(--fl-dur) var(--fl-ease);
        }
        .badge-float:nth-of-type(2) {
            animation-duration: 6.9s;
            animation-delay: -2.6s; /* desinkron sejak awal */
        }
        .badge-float:hover { box-shadow: 0 20px 38px -14px rgba(6, 20, 10, 0.3); }
        @keyframes flBob {
            0%, 100% { transform: translate3d(0, 0, 0) rotate(0deg); }
            50%      { transform: translate3d(0, -12px, 0) rotate(-1.2deg); }
        }

        /* ============ GRADIENT BANNER ============ */
        .gradient-banner {
            background: linear-gradient(to right, #eaf9ec, #f6fff7);
            background-size: 220% 100%;
            animation: flGradientDrift 16s ease-in-out infinite alternate;
        }
        @keyframes flGradientDrift {
            from { background-position: 0% 50%; }
            to   { background-position: 100% 50%; }
        }
        .gradient-banner .badge {
            transition: transform var(--fl-dur) var(--fl-ease-back),
                        box-shadow var(--fl-dur) var(--fl-ease);
        }
        .gradient-banner .badge:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 22px -12px rgba(6, 20, 10, 0.32);
        }
        /* motif ekonomi sirkular: ikon ⟳ berputar pelan */
        .gradient-banner .rounded-circle .rounded-circle {
            animation: flSpin 18s linear infinite;
        }
        @keyframes flSpin { to { transform: rotate(360deg); } }

        /* ============ BLOK DAMPAK (hijau) ============ */
        #manfaat .bg-fl-green.text-white {
            position: relative;
            isolation: isolate;
            overflow: hidden;
        }
        #manfaat .bg-fl-green.text-white::after {
            content: "";
            position: absolute;
            inset: -35%;
            z-index: -1;
            pointer-events: none;
            background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.20), transparent 55%);
            animation: flGlowDrift 18s ease-in-out infinite alternate;
        }
        @keyframes flGlowDrift {
            from { transform: translate3d(-4%, -3%, 0); }
            to   { transform: translate3d(6%, 5%, 0); }
        }

        /* ============ TIM ============ */
        .rounded-circle.overflow-hidden.shadow-fl-soft {
            transition: transform var(--fl-dur-slow) var(--fl-ease),
                        box-shadow var(--fl-dur-slow) var(--fl-ease);
        }
        .rounded-circle.overflow-hidden.shadow-fl-soft img {
            transition: transform 0.85s var(--fl-ease);
        }
        .d-inline-block.position-relative:hover > .rounded-circle.overflow-hidden.shadow-fl-soft {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 28px 48px -20px rgba(21, 66, 30, 0.35);
        }
        .d-inline-block.position-relative:hover > .rounded-circle.overflow-hidden.shadow-fl-soft img {
            transform: scale(1.08);
        }

        footer {
            background-color: #0f1410;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem;
        }
        footer a {
            display: inline-block;
            transition: color var(--fl-dur-fast) var(--fl-ease-soft),
                        transform var(--fl-dur) var(--fl-ease);
        }
        footer a:hover {
            color: #ffffff !important;
            transform: translateX(5px);
        }

        /* ============ NAVBAR ============ */
        .navbar-sticky-custom {
            box-shadow: 0 1px 0 rgba(6, 20, 10, 0.05);
            transition: box-shadow var(--fl-dur-slow) var(--fl-ease);
        }
        /* elevasi bayangan mengikuti scroll — murni CSS, tanpa JS */
        @supports (animation-timeline: scroll()) {
            .navbar-sticky-custom {
                animation: flNavElevate linear both;
                animation-timeline: scroll(root block);
                animation-range: 0 140px;
            }
        }
        @keyframes flNavElevate {
            from { box-shadow: 0 1px 0 rgba(6, 20, 10, 0.05); }
            to   { box-shadow: 0 12px 30px -14px rgba(6, 20, 10, 0.25); }
        }

        .navbar-brand { transition: transform var(--fl-dur) var(--fl-ease); }
        .navbar-brand:hover { transform: translateY(-1px); }
        .navbar-brand img { transition: transform var(--fl-dur-slow) var(--fl-ease-back); }
        .navbar-brand:hover img { transform: scale(1.07) rotate(-4deg); }

        .navbar-nav .nav-link {
            position: relative;
            transition: color var(--fl-dur-fast) var(--fl-ease-soft);
        }
        .navbar-nav .nav-link::after {
            content: "";
            position: absolute;
            left: 0.5rem;
            right: 0.5rem;
            bottom: 0.15rem;
            height: 2px;
            border-radius: 2px;
            background-color: var(--fl-green);
            transform: scaleX(0);
            transform-origin: right center;
            transition: transform var(--fl-dur) var(--fl-ease);
        }
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link:focus-visible::after {
            transform: scaleX(1);
            transform-origin: left center;
        }
        .navbar-nav .nav-link.hover-dark:hover,
        .navbar-nav .nav-link.hover-dark:focus-visible { color: #0f172a !important; }

        /* ============ MODAL ============ */
        .modal.fade .modal-dialog {
            transform: translateY(26px) scale(0.96);
            transition: transform var(--fl-dur) var(--fl-ease),
                        opacity var(--fl-dur) var(--fl-ease);
        }
        .modal.show .modal-dialog { transform: none; }

        /* ============ AI Chat Widget Button ============ */
        .chat-widget-btn {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--fl-green);
            color: white;
            box-shadow: 0 10px 24px -8px rgba(77, 180, 63, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            transition: transform var(--fl-dur) var(--fl-ease-back),
                        box-shadow var(--fl-dur) var(--fl-ease),
                        background-color var(--fl-dur-fast) linear;
        }
        /* denyut halus saat idle */
        .chat-widget-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: -1;
            border-radius: 50%;
            background-color: var(--fl-green);
            animation: flChatPulse 2.8s var(--fl-ease-soft) infinite;
        }
        @keyframes flChatPulse {
            0%   { transform: scale(1); opacity: 0.45; }
            70%  { transform: scale(1.8); opacity: 0; }
            100% { transform: scale(1.8); opacity: 0; }
        }
        .chat-widget-btn svg { transition: transform var(--fl-dur) var(--fl-ease-back); }
        .chat-widget-btn:hover {
            transform: translateY(-3px) scale(1.08);
            background-color: var(--fl-green-dark);
            color: white;
            box-shadow: 0 20px 38px -10px rgba(77, 180, 63, 0.7);
        }
        .chat-widget-btn:hover::before { opacity: 0; animation-play-state: paused; }
        .chat-widget-btn:hover svg { transform: rotate(-12deg) scale(1.1); }
        .chat-widget-btn:active { transform: translateY(-1px) scale(0.96); transition-duration: 0.09s; }

        /* ============ AKSESIBILITAS: hormati preferensi reduced motion ============ */
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
    <link href="{{ asset('css/page-transitions.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-S1B50GLPDP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-S1B50GLPDP');
    </script>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top navbar-sticky-custom py-3 bg-white">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <img src="{{ asset('images/logo_foodlink_hijau_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 40px;">
                <div class="lh-1">
                    <div class="fw-semibold">FoodLink</div>
                    <div class="text-muted" style="font-size: 0.75rem;">Ekosistem Ekonomi Sirkular</div>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none bg-white bg-opacity-50" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav gap-lg-4 text-sm mt-3 mt-lg-0 fw-medium">
                    <li class="nav-item"><a class="nav-link text-secondary hover-dark" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary hover-dark" href="#cara">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary hover-dark" href="#manfaat">Manfaat</a></li>
                    <li class="nav-item"><a class="nav-link text-secondary hover-dark" href="#kontak">Kontak</a></li>
                </ul>
            </div>

            <div class="d-none d-lg-block">
                <a href="#cta" class="btn btn-fl-primary shadow-sm text-decoration-none">Unduh Aplikasi</a>
            </div>
        </div>
    </nav>

    <div class="container py-4" id="main-container">

        <header class="mt-5">
            <div class="row align-items-center gy-5">

                <div class="col-lg-6">
                    <div class="d-inline-flex align-items-center gap-2 bg-fl-green-subtle text-fl-green px-3 py-1 rounded-pill small fw-medium w-auto mb-3" data-aos="fade-up" data-aos-delay="0">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M12 2v20"/></svg>
                        Ekosistem Ekonomi Sirkular
                    </div>

                    <h1 class="display-4 fw-bolder lh-1 mb-4" data-aos="fade-up" data-aos-delay="100">
                        Ubah <span class="text-fl-green">Limbah</span><br class="d-none d-md-block" /> Makanan Jadi <span class="text-fl-green">Peluang Bisnis</span>
                    </h1>

                    <p class="lead text-secondary mb-4" style="max-width: 550px;" data-aos="fade-up" data-aos-delay="200">
                        FoodLink adalah platform inovatif yang menghubungkan bisnis dan konsumen dalam ekosistem sirkular untuk mengurangi pemborosan makanan melalui <strong>Jual-Cepat</strong>, <strong>Donasi</strong>, dan <strong>Barter B2B</strong>.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-5" data-aos="fade-up" data-aos-delay="300">
                        <a href="#cta" class="btn btn-fl-primary d-inline-flex align-items-center gap-2 shadow-fl-soft">
                            Mulai Sekarang
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                        <a href="#fitur" class="btn btn-fl-outline d-inline-flex align-items-center gap-2">Pelajari Lebih</a>
                    </div>

                    <div class="d-flex align-items-center gap-5">
                        <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                            <div class="h3 fw-bolder text-fl-green mb-0">100+</div>
                            <small class="text-muted">Mitra Aktif</small>
                        </div>
                        <div class="text-center" data-aos="fade-up" data-aos-delay="450">
                            <div class="h3 fw-bolder text-fl-green mb-0">1 Ton+</div>
                            <small class="text-muted">Makanan Diselamatkan</small>
                        </div>
                        <div class="text-center" data-aos="fade-up" data-aos-delay="500">
                            <div class="h3 fw-bolder text-fl-green mb-0">100+</div>
                            <small class="text-muted">Kota Terjangkau</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end" data-aos="fade-left" data-aos-duration="1100" data-aos-delay="250">
                    <div class="phone-mockup me-lg-4">
                        <picture class="phone-screen">
                            <img src="https://i.ibb.co.com/nNq2SNHK/selamat-datang-foodlink.png" alt="App Preview" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        </picture>

                        <div class="badge-float" style="top: 20%; left: -4rem;">
                            <div class="rounded-circle bg-fl-green text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">⟳</div>
                            <div class="lh-sm">
                                <div class="fw-semibold small">Barter B2B</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Tanpa uang tunai</div>
                            </div>
                        </div>

                        <div class="badge-float" style="bottom: 40%; right: -4rem;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #e6faf0; color: var(--fl-green);">🍃</div>
                            <div class="lh-sm">
                                <div class="fw-semibold small">Eco Friendly</div>
                                <div class="text-muted" style="font-size: 0.7rem;">Zero waste</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="fitur" class="mt-5 pt-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="text-fl-green fw-bold small text-uppercase">Fitur Unggulan</div>
                <h2 class="display-6 fw-bolder mt-2">Tiga Pilar Ekosistem <span class="text-fl-green">FoodLink</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Solusi terintegrasi untuk mengatasi masalah limbah makanan dari berbagai sudut pandang.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 text-white" style="width: 48px; height: 48px; background: linear-gradient(to top right, #fbbf24, #fb923c);">⚡</div>
                            <div>
                                <h3 class="h5 fw-bold mb-2">Jual-Cepat</h3>
                                <p class="small text-secondary mb-0">Jual produk makanan mendekati kadaluarsa dengan harga diskon. Konsumen hemat, bisnis tidak rugi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="120">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-3 bg-danger bg-opacity-75 d-flex align-items-center justify-content-center flex-shrink-0 text-white" style="width: 48px; height: 48px;">❤</div>
                            <div>
                                <h3 class="h5 fw-bold mb-2">Donasi</h3>
                                <p class="small text-secondary mb-0">Salurkan kelebihan makanan ke yang membutuhkan. Berkontribusi untuk mengurangi kelaparan di komunitas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="240">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-3 bg-fl-green d-flex align-items-center justify-content-center flex-shrink-0 text-white" style="width: 48px; height: 48px;">⟳</div>
                            <div>
                                <h3 class="h5 fw-bold mb-2">Barter B2B</h3>
                                <p class="small text-secondary mb-0">Tukar kelebihan stok antar bisnis menggunakan sistem kredit internal. Hemat arus kas, bangun jaringan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="bg-white rounded-4 border p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">↑</div>
                        <div class="lh-sm">
                            <div class="fw-bold">Efisiensi Bisnis</div>
                            <div class="small text-secondary" style="font-size: 0.8rem;">Kurangi kerugian hingga 70%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white rounded-4 border p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">🔒</div>
                        <div class="lh-sm">
                            <div class="fw-bold">Keamanan Transaksi</div>
                            <div class="small text-secondary" style="font-size: 0.8rem;">Sistem kredit terpercaya</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white rounded-4 border p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">⏱️</div>
                        <div class="lh-sm">
                            <div class="fw-bold">Real-time</div>
                            <div class="small text-secondary" style="font-size: 0.8rem;">Notifikasi & tracking langsung</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="cara" class="mt-5 pt-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="text-fl-green fw-bold small text-uppercase">Cara Kerja</div>
                <h2 class="display-6 fw-bolder mt-2">Ekosistem <span class="text-fl-green">Sirkular</span> yang Simpel</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Hanya dalam 4 langkah, ubah kelebihan makanan menjadi nilai ekonomi baru.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">01</div>
                        <h3 class="h6 fw-bold mt-3">Daftar Bisnis</h3>
                        <p class="small text-secondary mt-2 mb-0">Kafe, restoran, toko kelontong—siapa saja dapat bergabung sebagai mitra FoodLink.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="110">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">02</div>
                        <h3 class="h6 fw-bold mt-3">Upload Surplus</h3>
                        <p class="small text-secondary mt-2 mb-0">Masukkan kelebihan stok makanan, tentukan Jual-Cepat / Donasi / Barter.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="220">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">03</div>
                        <h3 class="h6 fw-bold mt-3">Match & Connect</h3>
                        <p class="small text-secondary mt-2 mb-0">Sistem AI mencocokkan kebutuhan antar mitra — tukar kue dengan sayuran.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="330">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">04</div>
                        <h3 class="h6 fw-bold mt-3">Selesai!</h3>
                        <p class="small text-secondary mt-2 mb-0">Transaksi tercatat, kredit terupdate, dan makanan terselamatkan.</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-4 gradient-banner p-5" data-aos="zoom-in" data-aos-duration="1000">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h3 class="fw-bolder mb-3">Paradigma Baru: <span class="text-fl-green">Ekonomi Sirkular B2B</span></h3>
                        <p class="text-secondary">Berbeda dengan model linear tradisional, FoodLink menciptakan siklus ekonomi tertutup dimana pelaku usaha saling terhubung. Sistem kredit internal memungkinkan transaksi tanpa uang tunai.</p>

                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <span class="badge bg-white text-dark border rounded-pill px-3 py-2 fw-normal">💰 Hemat Biaya</span>
                            <span class="badge bg-white text-dark border rounded-pill px-3 py-2 fw-normal">🤝 Kolaborasi</span>
                            <span class="badge bg-white text-dark border rounded-pill px-3 py-2 fw-normal">🌱 Berkelanjutan</span>
                        </div>
                    </div>
                    <div class="col-md-5 d-flex justify-content-center justify-content-md-end mt-4 mt-md-0">
                        <div class="rounded-circle bg-white shadow-fl-card d-flex align-items-center justify-content-center" style="width: 224px; height: 224px;">
                            <div class="rounded-circle bg-fl-green text-white d-flex align-items-center justify-content-center display-4" style="width: 112px; height: 112px;">⟳</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="manfaat" class="mt-5 pt-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="text-fl-green fw-bold small text-uppercase">Manfaat</div>
                <h2 class="display-6 fw-bolder mt-2">Lebih dari Sekedar <span class="text-fl-green">Aplikasi</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">FoodLink adalah alat intelijen bisnis yang membantu Anda menghemat, berkolaborasi, dan berkembang.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="d-flex gap-3">
                            <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">💼</div>
                            <div>
                                <h4 class="h6 fw-bold">Hemat Arus Kas</h4>
                                <p class="small text-secondary mb-0 mt-2">Gunakan sistem kredit untuk barter tanpa uang tunai, menjaga likuiditas bisnis.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="120">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="d-flex gap-3">
                            <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">📊</div>
                            <div>
                                <h4 class="h6 fw-bold">Intelijen Bisnis</h4>
                                <p class="small text-secondary mb-0 mt-2">Dashboard analitik untuk memantau tren, mengoptimalkan stok, dan prediksi permintaan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="240">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="d-flex gap-3">
                            <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">🌐</div>
                            <div>
                                <h4 class="h6 fw-bold">Jaringan Luas</h4>
                                <p class="small text-secondary mb-0 mt-2">Terhubung dengan ribuan mitra bisnis dalam ekosistem yang saling menguntungkan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-4 bg-fl-green text-white p-5 text-center shadow-fl-soft" data-aos="zoom-in" data-aos-duration="1000">
                <h3 class="h2 fw-bold">Dampak Nyata FoodLink</h3>
                <p class="small opacity-75 mx-auto" style="max-width: 600px;">Bersama-sama kita menciptakan perubahan yang berarti</p>

                <div class="row justify-content-center mt-5 gy-4">
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
                        <div class="display-6 fw-bold">{{ number_format($mitraCount ?? 0) }}</div>
                        <div class="small">Restoran & Kafe</div>
                    </div>
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="90">
                        <div class="display-6 fw-bold">{{ number_format($userCount ?? 0) }}</div>
                        <div class="small">Pengguna Aktif</div>
                    </div>
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="180">
                        <div class="display-6 fw-bold">{{ number_format($makananDiselamatkan ?? 0) }}</div>
                        <div class="small">Makanan Diselamatkan</div>
                    </div>
                    <div class="col-md-3" data-aos="fade-up" data-aos-delay="270">
                        <div class="display-6 fw-bold">{{ number_format($visitorCount ?? 0) }}</div>
                        <div class="small">Total Pengunjung</div>
                        @if(isset($dbError) && $dbError)
                            <div class="text-danger mt-2" style="font-size: 0.65rem; word-break: break-all; opacity: 0.7;">
                                {{ $dbError }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-5 rounded-4 bg-white p-5 shadow-sm border" data-aos="fade-up" data-aos-duration="1200">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0" data-aos="fade-right" data-aos-delay="100">
                    <div class="small text-fl-green fw-bold">Tersedia di Android & iOS</div>
                    <h2 class="display-6 fw-bolder mt-2">Mulai Kurangi <span class="text-fl-green">Limbah Makanan</span> Hari Ini</h2>
                    <p class="text-secondary mt-3">Bergabung dengan ribuan mitra bisnis dan konsumen yang sudah merasakan manfaat ekosistem FoodLink</p>

                    <div class="d-flex gap-3 mt-4">
                        {{-- Trigger Modal --}}
                        <button
                            class="btn btn-success d-inline-flex align-items-center gap-2"
                            style="background-color: #15803d; border:none;"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDownload">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.18 23.76A1.5 1.5 0 0 0 5 23.57l11.34-6.54-3.19-3.19zM.5 1.4A1.5 1.5 0 0 0 0 2.5v19a1.5 1.5 0 0 0 .5 1.1l.08.07 10.64-10.64v-.25L.58 1.33zM20.1 10.4l-3.03-1.75-3.54 3.54 3.54 3.54 3.06-1.77a1.5 1.5 0 0 0 0-2.56zM3.18.24l13.89 8.01-3.19 3.19L3.18.24z"/>
                            </svg>
                            Download Play Store
                        </button>
                    </div>
                    <div class="small text-secondary mt-4">10,000+ Pengguna bergabung bulan ini</div>
                </div>

                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end" data-aos="fade-left" data-aos-delay="250">
                    <div class="phone-mockup me-lg-4">
                        <picture class="phone-screen">
                            <img src="https://i.ibb.co.com/8n9VvhQw/Screenshot-2025-12-08-014225.png" alt="App Preview" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        </picture>
                    </div>
                </div>
            </div>
        </section>

        <div class="text-center mt-5 mb-5" data-aos="zoom-in" data-aos-delay="50">
            <a id="cta" href="{{ route('mitra.login') }}" class="btn btn-fl-outline rounded-pill px-4 py-3">Daftar Sebagai Mitra →</a>
        </div>

        @if(isset($anggotaTim) && $anggotaTim->count() > 0)
        <!-- Dynamic Team Section -->
        <section class="mt-5 pt-5 border-top border-opacity-10 border-success">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="text-fl-green fw-bold small text-uppercase">Tim Penggerak</div>
                <h2 class="display-6 fw-bolder mt-2">Dibalik <span class="text-fl-green">FoodLink</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Individu-individu yang mendedikasikan diri untuk mengurangi limbah makanan.</p>
            </div>

            <div class="row g-4 justify-content-center">
                @foreach($anggotaTim as $anggota)
                <div class="col-6 col-md-4 col-lg-3 text-center" data-aos="fade-up" data-aos-delay="{{ (($loop->iteration - 1) % 4) * 90 }}">
                    <div class="mb-3 d-inline-block position-relative">
                        <div class="rounded-circle overflow-hidden shadow-fl-soft" style="width: 150px; height: 150px; border: 4px solid white; background-color: var(--fl-bg);">
                            @if($anggota->foto_url)
                                <img src="{{ $anggota->foto_url }}" alt="{{ $anggota->nama }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-fl-green-subtle text-fl-green fs-1">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="position-absolute bottom-0 end-0 bg-fl-green rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 4px; transform: translate(-10px, -5px);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-100 h-100"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                    </div>
                    <h3 class="h5 fw-bold mb-1">{{ $anggota->nama }}</h3>
                    <p class="small text-secondary">{{ $anggota->jabatan }}</p>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    <footer id="kontak" class="text-white pt-5 pb-4 px-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ asset('images/logo_foodlink_putih_tanpa_background.png') }}" alt="Foodlink Logo" class="img-fluid" style="max-height: 45px;">
                        <div class="lh-1">
                            <div class="fw-semibold">FoodLink</div>
                            <div class="small text-white-50">Platform inovatif ekosistem ekonomi sirkular</div>
                        </div>
                    </div>
                    <div class="small text-white-50 d-flex flex-column gap-2">
                        <div>foodlinkmeal@gmail.com</div>
                        <div>+62 882 1550 2344</div>
                        <div>Yogyakarta, Indonesia</div>
                    </div>
                </div>

                <div class="col-md-2 offset-md-1">
                    <h5 class="h6 fw-bold mb-3">Produk</h5>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                        <li><a href="#" class="text-reset text-decoration-none">Fitur</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Cara Kerja</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Harga</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">FAQ</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h5 class="h6 fw-bold mb-3">Perusahaan</h5>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                        <li><a href="#" class="text-reset text-decoration-none">Tentang Kami</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Karir</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Blog</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Press Kit</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h5 class="h6 fw-bold mb-3">Legal</h5>
                    <ul class="list-unstyled small text-white-50 d-flex flex-column gap-2">
                        <li><a href="#" class="text-reset text-decoration-none">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-reset text-decoration-none">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-top border-secondary border-opacity-25 mt-5 pt-4 text-center small text-white-50">
                © 2024 FoodLink. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Modal Konfirmasi Download -->
    <div class="modal fade" id="modalDownload" tabindex="-1" aria-labelledby="modalDownloadLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-fl-card">
                <div class="modal-body text-center p-5">

                    <div class="rounded-circle bg-fl-green-subtle d-inline-flex align-items-center justify-content-center mb-4" style="width: 72px; height: 72px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="var(--fl-green)">
                            <path d="M3.18 23.76A1.5 1.5 0 0 0 5 23.57l11.34-6.54-3.19-3.19zM.5 1.4A1.5 1.5 0 0 0 0 2.5v19a1.5 1.5 0 0 0 .5 1.1l.08.07 10.64-10.64v-.25L.58 1.33zM20.1 10.4l-3.03-1.75-3.54 3.54 3.54 3.54 3.06-1.77a1.5 1.5 0 0 0 0-2.56zM3.18.24l13.89 8.01-3.19 3.19L3.18.24z"/>
                        </svg>
                    </div>

                    <h5 class="fw-bold mb-2">Unduh Aplikasi FoodLink?</h5>
                    <p class="text-secondary small mb-1">Anda akan mengunduh file APK FoodLink untuk Android.</p>
                    <p class="text-secondary small mb-4">Pastikan perangkat Anda mengizinkan instalasi dari sumber eksternal.</p>

                    <div id="apkVersionInfo" class="d-inline-flex align-items-center gap-2 bg-fl-green-subtle text-fl-green px-3 py-2 rounded-pill small fw-medium mb-4">
                        📦 Memuat info versi...
                    </div>

                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-fl-outline px-4" data-bs-dismiss="modal">Batal</button>

                        {{-- ✅ PERBAIKAN: pakai button + JavaScript, bukan <a> dengan data-bs-dismiss --}}
                        <button type="button" class="btn btn-fl-primary px-4 d-inline-flex align-items-center gap-2" id="btnDownload">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Ya, Unduh Sekarang
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Floating AI Chat Widget -->
    <a href="{{ route('chat.ai') }}" class="chat-widget-btn" title="Tanya FoodLink AI">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ asset('js/page-transitions.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Initialize AOS
            AOS.init({
                once: true,
                mirror: false,
                offset: 80,
                duration: 900,
                delay: 0,
                easing: 'ease-out-back',
                anchorPlacement: 'top-bottom'
            });

            // Hitung ulang posisi setelah gambar (lazy) selesai dimuat agar trigger tetap akurat
            window.addEventListener('load', function () {
                AOS.refresh();
            });

            // ✅ Fix Download
            const btnDownload = document.getElementById('btnDownload');
            if (btnDownload) {
                btnDownload.addEventListener('click', function () {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalDownload'));
                    modal.hide();

                    setTimeout(function () {
                        window.open(
                            'https://github.com/stevenhuge/foodlink-web/releases/latest/download/FoodLink.apk',
                            '_blank'
                        );
                    }, 300);
                });
            }

            // ✅ Fetch latest release info
            fetch('https://api.github.com/repos/stevenhuge/foodlink-web/releases/latest')
                .then(response => response.json())
                .then(data => {
                    if (data && data.tag_name) {
                        const versionInfo = document.getElementById('apkVersionInfo');
                        if (versionInfo) {
                            let sizeStr = '~25 MB';
                            if (data.assets && data.assets.length > 0) {
                                const sizeMB = (data.assets[0].size / (1024 * 1024)).toFixed(1);
                                sizeStr = sizeMB + ' MB';
                            }
                            versionInfo.innerHTML = `📦 FoodLink-${data.tag_name}.apk &middot; ${sizeStr}`;
                        }
                    }
                })
                .catch(error => console.error('Error fetching release info:', error));


        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success_register_user'))
            Swal.fire({
                title: 'Registrasi Berhasil!',
                text: '{{ session('success_register_user') }}',
                icon: 'success',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#4DB43F'
            });
        @endif
    </script>

</body>
</html>