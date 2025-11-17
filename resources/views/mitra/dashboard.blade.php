@extends('mitra.layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 text-dark fw-bold">Dashboard Mitra</h1>
        <p class="lead text-muted">Selamat datang, {{ $mitra->nama_mitra }}. Kelola produk surplus Anda dengan mudah.</p>
    </div>
    <div class="card bg-success-subtle border-success border-2 mb-5 shadow-sm">
        <div class="card-body d-flex align-items-center p-4">
            <i class="bi bi-patch-check-fill text-success fs-2 me-3"></i>
            <div>
                <h5 class="card-title text-success mb-0 fw-semibold">Akun Terverifikasi</h5>
                <p class="card-text text-success-emphasis mb-0">Akun Anda telah DIVERIFIKASI. Anda siap untuk memulai aktivitas.</p>
            </div>
        </div>
    </div>
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
    <div class="mt-5 pt-3">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <h3 class="mb-0 text-dark">Penjualan Produk Terbaru</h3>
            <a href="{{ route('mitra.riwayat.index') }}" class="btn btn-outline-primary btn-sm fw-bold">Lihat Semua Riwayat</a>
        </div>

        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">

                    @forelse ($penjualanTerbaru as $detail)
                        <li class="list-group-item bg-light d-flex flex-wrap justify-content-between align-items-center p-3">
                            <div>
                                <strong class="text-dark">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</strong>
                                terjual
                                <span class="fw-bold text-primary">{{ $detail->jumlah }}</span> unit
                            </div>
                            <small class="text-muted">
                                {{ $detail->transaksi->waktu_pemesanan->format('d M Y, H:i') }}
                            </small>
                        </li>
                    @empty
                        <li class="list-group-item bg-light text-center text-muted p-4">
                            Belum ada produk yang terjual.
                        </li>
                    @endforelse

                </ul>
            </div>
        </div>
        </div>
    </div>

<style>
    /* ... (CSS Anda tidak berubah) ... */
</style>

@endsection
