@extends('mitra.layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
<div class="container py-5">
    <!-- START: Header & Welcome Message -->
    <div class="text-center mb-5">
        <!-- Menggunakan warna gelap yang tegas dan font-weight tebal -->
        <h1 class="display-5 text-dark fw-bold">Dashboard Mitra - Contoh aku rubah disini</h1>
        <p class="lead text-muted">Selamat datang, {{ $mitra->nama_mitra }}. Kelola produk surplus Anda dengan mudah.</p>
    </div>
    <!-- END: Header & Welcome Message -->

    <!-- START: Verification Status Banner (Dibuat lebih menonjol dan elegan) -->
    <div class="card bg-success-subtle border-success border-2 mb-5 shadow-sm">
        <div class="card-body d-flex align-items-center p-4">
            <!-- Menggunakan ikon Bootstrap (asumsi Bootstrap Icons tersedia atau ganti dengan Font Awesome jika diperlukan) -->
            <i class="bi bi-patch-check-fill text-success fs-2 me-3"></i>
            <div>
                <h5 class="card-title text-success mb-0 fw-semibold">Akun Terverifikasi</h5>
                <p class="card-text text-success-emphasis mb-0">Akun Anda telah DIVERIFIKASI. Anda siap untuk memulai aktivitas.</p>
            </div>
        </div>
    </div>
    <!-- END: Verification Status Banner -->

    <!-- START: Menu Aksi Cepat (Action Cards) -->
    <h3 class="mb-4 text-secondary border-bottom pb-2">Aksi Cepat</h3>
    <div class="row g-4 mb-5">

        {{-- Card 1: Tambah Produk --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('mitra.produk.create') }}" class="card action-card shadow-lg h-100 border-0 bg-primary text-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-plus-circle-fill fs-1 mb-2"></i>
                    <h5 class="card-title fw-bold">Tambah Produk Baru</h5>
                    <p class="card-text small">Jual Cepat / Donasi</p>
                </div>
            </a>
        </div>

        {{-- Card 2: Kelola Produk --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('mitra.produk.index') }}" class="card action-card shadow-lg h-100 border-0 bg-info text-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-box-seam-fill fs-1 mb-2"></i>
                    <h5 class="card-title fw-bold">Kelola Produk Saya</h5>
                    <p class="card-text small">Lihat, Edit, dan Hapus</p>
                </div>
            </a>
        </div>

        {{-- Card 3: Marketplace Barter --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('mitra.barter.index') }}" class="card action-card shadow-lg h-100 border-0 bg-warning text-dark">
                <div class="card-body text-center p-4">
                    <i class="bi bi-arrow-left-right fs-1 mb-2"></i>
                    <h5 class="card-title fw-bold">Marketplace Barter</h5>
                    <p class="card-text small">Tukarkan produk surplus</p>
                </div>
            </a>
        </div>

        {{-- Card 4: Inbox Penawaran Barter --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="{{ route('mitra.barter.inbox') }}" class="card action-card shadow-lg h-100 border-0 bg-danger text-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-envelope-open-fill fs-1 mb-2"></i>
                    <h5 class="card-title fw-bold">Inbox Penawaran</h5>
                    <p class="card-text small">Cek pesan barter masuk</p>
                </div>
            </a>
        </div>
    </div>
    <!-- END: Menu Aksi Cepat (Action Cards) -->

    <!-- START: Riwayat Transaksi Section -->
    <div class="mt-5 pt-3">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <h3 class="mb-0 text-dark">Riwayat Transaksi</h3>
            <!-- Tombol aksi untuk navigasi ke halaman riwayat lengkap -->
            <a href="#" class="btn btn-outline-primary btn-sm fw-bold">Lihat Semua Riwayat</a>
        </div>

        <div class="card p-4 shadow-sm border-0 bg-light">
            <p class="text-muted mb-0">Belum ada riwayat transaksi yang tercatat.</p>
            <p class="text-muted small"><em>(Semua produk yang telah diambil/dibarter oleh pengguna akan muncul di sini...)</em></p>
        </div>
    </div>
    <!-- END: Riwayat Transaksi Section -->

</div>

<style>
    /* Styling kustom untuk tampilan yang lebih modern */

    /* Menggunakan variabel warna agar mudah diubah */
    :root {
        --bs-primary: #1e70bf; /* Warna Biru Utama yang lebih dalam */
        --bs-info: #00bcd4;    /* Warna Info Cyan yang cerah */
        --bs-warning: #ffc107; /* Warna Kuning Warning */
        --bs-danger: #e74c3c;  /* Warna Merah Danger yang tegas */
        --bs-success-subtle: #d1e7dd;
        --bs-success-emphasis: #0f5132;
    }

    .bg-primary { background-color: var(--bs-primary) !important; }
    .bg-info { background-color: var(--bs-info) !important; }
    .bg-warning { background-color: var(--bs-warning) !important; }
    .bg-danger { background-color: var(--bs-danger) !important; }
    .bg-success-subtle { background-color: var(--bs-success-subtle) !important; }
    .text-success-emphasis { color: var(--bs-success-emphasis) !important; }

    /* Action Card Styling */
    .action-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem; /* Sudut lebih membulat */
        text-decoration: none; /* Menghilangkan underline */
        height: 100%; /* Memastikan tinggi kartu sama di grid */
    }

    .action-card:hover {
        transform: translateY(-5px); /* Efek 'lift' pada hover */
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.175) !important; /* Bayangan yang lebih besar */
    }

    /* Memastikan teks di dalam card yang berwarna memiliki kontras yang baik */
    .action-card.bg-primary, .action-card.bg-info, .action-card.bg-danger {
        color: white !important;
    }

    /* Mengubah warna teks warning card agar tetap kontras */
    .action-card.bg-warning {
        color: #343a40 !important;
    }

    /* Efek hover khusus untuk card warning */
    .action-card.bg-warning:hover {
        background-color: #f7a700 !important; /* Sedikit lebih gelap */
        color: white !important;
    }

    /* Ikon */
    .action-card i {
        display: block;
        margin-bottom: 0.5rem;
    }
</style>

<!-- Tambahkan referensi Bootstrap Icons jika belum ada di layout utama -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"> -->
<script>
    // Contoh untuk menambahkan class ikon jika diperlukan, atau pastikan sudah ada di 'mitra.layouts.app'
    // Ikon yang digunakan: bi-patch-check-fill, bi-plus-circle-fill, bi-box-seam-fill, bi-arrow-left-right, bi-envelope-open-fill
</script>
@endsection
