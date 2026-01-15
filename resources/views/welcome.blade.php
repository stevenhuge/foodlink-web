<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FoodLink — Landing (Demo)</title>
    <meta name="description" content="FoodLink — Ekosistem Ekonomi Sirkular (demo)"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --fl-green: #4DB43F;
            --fl-green-dark: #3aa233;
            --fl-bg: #f5fbf6;
            --fl-muted: #94a3a1;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--fl-bg);
            color: #0f172a; /* Slate-900 equivalent */
        }

        /* Custom Colors & Utilities */
        .text-fl-green { color: var(--fl-green) !important; }
        .bg-fl-green { background-color: var(--fl-green) !important; }
        .bg-fl-green-subtle { background-color: rgba(77, 180, 63, 0.1) !important; }

        .btn-fl-primary {
            background-color: var(--fl-green);
            color: white;
            border: none;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .btn-fl-primary:hover {
            background-color: var(--fl-green-dark);
            color: white;
            transform: translateY(-2px);
        }

        .btn-fl-outline {
            background-color: transparent;
            border: 1px solid var(--fl-green);
            color: var(--fl-green);
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            border-radius: 0.5rem;
        }
        .btn-fl-outline:hover {
            background-color: var(--fl-green);
            color: white;
        }

        /* Shadows & Cards */
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

        /* Mockup Styles */
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
            box-shadow: 0 10px 30px rgba(6,20,10,0.08);
            margin: 0 auto;
        }
        .phone-screen {
            position: absolute;
            inset: 0;
            border-radius: 36px;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        /* Floating Badges on Phone */
        .badge-float {
            position: absolute;
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 20;
            max-width: 200px;
        }

        /* Sections */
        .gradient-banner {
            background: linear-gradient(to right, #eaf9ec, #f6fff7);
        }

        footer {
            background-color: #0f1410;
            border-top-left-radius: 1.5rem;
            border-top-right-radius: 1.5rem; /* Optional symmetry */
        }
<<<<<<< HEAD

        /* NAVIGASI STICKY */
        .navbar-sticky-custom {
            background-color: rgba(245, 251, 246, 0.95); /* Warna sama dengan body tapi agak transparan */
            backdrop-filter: blur(10px); /* Efek blur kaca */
            border-bottom: 1px solid rgba(0,0,0,0.03); /* Garis tipis di bawah */
        }
=======
>>>>>>> 25c0b97 (update json get profile)
    </style>
</head>
<body>

<<<<<<< HEAD
=======
    <nav class="navbar navbar-expand-lg navbar-light sticky-top navbar-sticky-custom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                <div class="rounded-circle bg-fl-green text-white d-flex align-items-center justify-content-center shadow-fl-soft fw-bold" style="width: 40px; height: 40px;">F</div>
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

>>>>>>> c95dd3d09969b592f82e92a9d0ef155ef561543f
    <div class="container py-4">

        <nav class="navbar navbar-expand-lg navbar-light bg-transparent p-0">
            <div class="container-fluid px-0">
                <a class="navbar-brand d-flex align-items-center gap-3" href="#">
                    <div class="rounded-circle bg-fl-green text-white d-flex align-items-center justify-content-center shadow-fl-soft fw-bold" style="width: 40px; height: 40px;">F</div>
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

        <header class="mt-5">
            <div class="row align-items-center gy-5">

                <div class="col-lg-6">
                    <div class="d-inline-flex align-items-center gap-2 bg-fl-green-subtle text-fl-green px-3 py-1 rounded-pill small fw-medium w-auto mb-3">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M12 2v20"/></svg>
                        Ekosistem Ekonomi Sirkular
                    </div>

                    <h1 class="display-4 fw-bolder lh-1 mb-4">
                        Ubah <span class="text-fl-green">Limbah</span><br class="d-none d-md-block" /> Makanan Jadi <span class="text-fl-green">Peluang Bisnis</span>
                    </h1>

                    <p class="lead text-secondary mb-4" style="max-width: 550px;">
                        FoodLink adalah platform inovatif yang menghubungkan bisnis dan konsumen dalam ekosistem sirkular untuk mengurangi pemborosan makanan melalui <strong>Jual-Cepat</strong>, <strong>Donasi</strong>, dan <strong>Barter B2B</strong>.
                    </p>

                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <a href="#cta" class="btn btn-fl-primary d-inline-flex align-items-center gap-2 shadow-fl-soft">
                            Mulai Sekarang
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                        <a href="#fitur" class="btn btn-fl-outline d-inline-flex align-items-center gap-2">Pelajari Lebih</a>
                    </div>

                    <div class="d-flex align-items-center gap-5">
                        <div class="text-center">
                            <div class="h3 fw-bolder text-fl-green mb-0">10K+</div>
                            <small class="text-muted">Mitra Aktif</small>
                        </div>
                        <div class="text-center">
                            <div class="h3 fw-bolder text-fl-green mb-0">50Ton</div>
                            <small class="text-muted">Makanan Diselamatkan</small>
                        </div>
                        <div class="text-center">
                            <div class="h3 fw-bolder text-fl-green mb-0">100+</div>
                            <small class="text-muted">Kota Terjangkau</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end">
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
            <div class="text-center mb-5">
                <div class="text-fl-green fw-bold small text-uppercase">Fitur Unggulan</div>
                <h2 class="display-6 fw-bolder mt-2">Tiga Pilar Ekosistem <span class="text-fl-green">FoodLink</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Solusi terintegrasi untuk mengatasi masalah limbah makanan dari berbagai sudut pandang.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
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

                <div class="col-md-4">
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

                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="bg-white rounded-4 border p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">↑</div>
                        <div class="lh-sm">
                            <div class="fw-bold">Efisiensi Bisnis</div>
                            <div class="small text-secondary" style="font-size: 0.8rem;">Kurangi kerugian hingga 70%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded-4 border p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-fl-green-subtle text-fl-green d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">🔒</div>
                        <div class="lh-sm">
                            <div class="fw-bold">Keamanan Transaksi</div>
                            <div class="small text-secondary" style="font-size: 0.8rem;">Sistem kredit terpercaya</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
            <div class="text-center mb-5">
                <div class="text-fl-green fw-bold small text-uppercase">Cara Kerja</div>
                <h2 class="display-6 fw-bolder mt-2">Ekosistem <span class="text-fl-green">Sirkular</span> yang Simpel</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Hanya dalam 4 langkah, ubah kelebihan makanan menjadi nilai ekonomi baru.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">01</div>
                        <h3 class="h6 fw-bold mt-3">Daftar Bisnis</h3>
                        <p class="small text-secondary mt-2 mb-0">Kafe, restoran, toko kelontong—siapa saja dapat bergabung sebagai mitra FoodLink.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">02</div>
                        <h3 class="h6 fw-bold mt-3">Upload Surplus</h3>
                        <p class="small text-secondary mt-2 mb-0">Masukkan kelebihan stok makanan, tentukan Jual-Cepat / Donasi / Barter.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">03</div>
                        <h3 class="h6 fw-bold mt-3">Match & Connect</h3>
                        <p class="small text-secondary mt-2 mb-0">Sistem AI mencocokkan kebutuhan antar mitra — tukar kue dengan sayuran.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-fl-card rounded-4 p-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">04</div>
                        <h3 class="h6 fw-bold mt-3">Selesai!</h3>
                        <p class="small text-secondary mt-2 mb-0">Transaksi tercatat, kredit terupdate, dan makanan terselamatkan.</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 rounded-4 gradient-banner p-5">
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
            <div class="text-center mb-5">
                <div class="text-fl-green fw-bold small text-uppercase">Manfaat</div>
                <h2 class="display-6 fw-bolder mt-2">Lebih dari Sekedar <span class="text-fl-green">Aplikasi</span></h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">FoodLink adalah alat intelijen bisnis yang membantu Anda menghemat, berkolaborasi, dan berkembang.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <div class="col-md-4">
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

            <div class="mt-5 rounded-4 bg-fl-green text-white p-5 text-center shadow-fl-soft">
                <h3 class="h2 fw-bold">Dampak Nyata FoodLink</h3>
                <p class="small opacity-75 mx-auto" style="max-width: 600px;">Bersama-sama kita menciptakan perubahan yang berarti</p>

                <div class="row justify-content-center mt-5 gy-4">
                    <div class="col-md-3">
                        <div class="display-6 fw-bold">500+</div>
                        <div class="small">Restoran & Kafe</div>
                    </div>
                    <div class="col-md-3">
                        <div class="display-6 fw-bold">50K+</div>
                        <div class="small">Pengguna Aktif</div>
                    </div>
                    <div class="col-md-3">
                        <div class="display-6 fw-bold">100Ton</div>
                        <div class="small">Makanan Diselamatkan</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-5 rounded-4 bg-white p-5 shadow-sm border">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="small text-fl-green fw-bold">Tersedia di Android & iOS</div>
                    <h2 class="display-6 fw-bolder mt-2">Mulai Kurangi <span class="text-fl-green">Limbah Makanan</span> Hari Ini</h2>
                    <p class="text-secondary mt-3">Bergabung dengan ribuan mitra bisnis dan konsumen yang sudah merasakan manfaat ekosistem FoodLink.</p>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn btn-dark d-inline-flex align-items-center">Download App Store</button>
                        <button class="btn btn-success d-inline-flex align-items-center" style="background-color: #15803d; border:none;">Download Play Store</button>
                    </div>
                    <div class="small text-secondary mt-4">10,000+ Pengguna bergabung bulan ini</div>
                </div>


                <div class="col-lg-5 d-flex justify-content-center justify-content-lg-end">
                    <div class="phone-mockup me-lg-4">
                        <picture class="phone-screen">
                            <img src="https://i.ibb.co.com/8n9VvhQw/Screenshot-2025-12-08-014225.png" alt="App Preview" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                        </picture>
                    </div>
                </div>
<<<<<<< HEAD
<<<<<<< HEAD
            </div>
=======
>>>>>>> 25c0b97 (update json get profile)
=======
=======
            </div>
>>>>>>> 0bff819802a04fcb220d58d1439cae40d856a154
>>>>>>> c95dd3d09969b592f82e92a9d0ef155ef561543f
        </section>

        <div class="text-center mt-5 mb-5">
            <a id="cta" href="#daftar" class="btn btn-fl-outline rounded-pill px-4 py-3">Daftar Sebagai Mitra →</a>
        </div>

<<<<<<< HEAD
<<<<<<< HEAD
    </div>

    <footer id="kontak" class="text-white pt-5 pb-4 px-4">
=======
    </div> <footer id="kontak" class="text-white pt-5 pb-4 px-4">
>>>>>>> 25c0b97 (update json get profile)
=======
    </div> <footer id="kontak" class="text-white pt-5 pb-4 px-4">
=======
    </div>

    <footer id="kontak" class="text-white pt-5 pb-4 px-4">
>>>>>>> 0bff819802a04fcb220d58d1439cae40d856a154
>>>>>>> c95dd3d09969b592f82e92a9d0ef155ef561543f
        <div class="container">
            <div class="row g-5">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-fl-green text-white fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">F</div>
                        <div class="lh-1">
                            <div class="fw-semibold">FoodLink</div>
                            <div class="small text-white-50">Platform inovatif ekosistem ekonomi sirkular</div>
                        </div>
                    </div>
                    <div class="small text-white-50 d-flex flex-column gap-2">
                        <div>hello@foodlink.id</div>
                        <div>+62 812 3456 7890</div>
                        <div>Jakarta, Indonesia</div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
