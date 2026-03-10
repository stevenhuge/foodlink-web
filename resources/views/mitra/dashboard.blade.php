@extends('mitra.layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
    <div class="container py-4">

        <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bell text-primary me-2"></i>Inbox Pengumuman</h5>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifikasi as $notif)
                    <div class="list-group-item p-3">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <h6 class="mb-1 fw-bold text-dark">{{ $notif->judul }}</h6>
                            <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small text-secondary">{{ $notif->pesan }}</p>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        Belum ada pengumuman baru.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Dashboard Mitra</h2>
            <p class="text-muted mb-0">Selamat datang kembali, <strong>{{ $mitra->nama_mitra }}</strong> 👋</p>
        </div>
        <div class="d-none d-md-block">
            <div class="bg-white border px-3 py-2 rounded-pill shadow-sm text-muted small">
                <i class="bi bi-calendar-event me-2 text-primary"></i> {{ date('d F Y') }}
            </div>
        </div>
    </div>

    <div class="card border-0 mb-4 p-4 border-radius-xl" style="background: linear-gradient(135deg, var(--foodlink-primary) 0%, var(--foodlink-primary-hover) 100%);">
        <div class="row align-items-center">
            <div class="col-md-8 text-white">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-white text-success rounded-pill px-3 py-2 shadow-sm">
                        <i class="bi bi-patch-check-fill me-1"></i> Mitra Terverifikasi
                    </span>
                </div>
                <h4 class="fw-bold mb-2">Ayo Tingkatkan Penjualan Anda</h4>
                <p class="mb-0 text-white-50">Kelola toko Anda, tambah produk menarik, dan jangkau lebih banyak pelanggan hari ini.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="fas fa-store text-white opacity-25" style="font-size: 6rem; transform: rotate(-10deg);"></i>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <h5 class="fw-bold text-dark mb-0">Akses Cepat</h5>
        </div>
        <div class="row g-3">
            {{-- Menu 1: Tambah Produk --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.produk.create') }}" class="card h-100 hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Tambah Produk</h6>
                        <p class="text-muted small mb-0">Jual / Donasi</p>
                    </div>
                </a>
            </div>

            {{-- Menu 2: Kelola Produk --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.produk.index') }}" class="card h-100 hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Produk Saya</h6>
                        <p class="text-muted small mb-0">Kelola Stok</p>
                    </div>
                </a>
            </div>

            {{-- Menu 3: Barter --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.barter.index') }}" class="card h-100 hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Barter Market</h6>
                        <p class="text-muted small mb-0">Tukar Produk</p>
                    </div>
                </a>
            </div>

            {{-- Menu 4: Inbox --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.barter.inbox') }}" class="card h-100 hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger mx-auto mb-3">
                            <i class="far fa-envelope"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Inbox Barter</h6>
                        <p class="text-muted small mb-0">Cek Pesan</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Penjualan Terakhir</h5>
                    <a href="{{ route('mitra.riwayat.index') }}" class="btn btn-sm btn-light border px-3 rounded-pill fw-medium text-secondary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0">
                            <thead class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="background-color: transparent;">
                                <tr>
                                    <th class="ps-4 text-muted font-weight-bold border-bottom py-3">Produk</th>
                                    <th class="text-muted font-weight-bold border-bottom py-3">Jumlah</th>
                                    <th class="text-muted font-weight-bold border-bottom py-3">Tanggal</th>
                                    <th class="text-muted font-weight-bold border-bottom py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penjualanTerbaru as $detail)
                                    <tr>
                                        <td class="ps-4 py-3 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-shopping-bag text-secondary fs-5"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block text-dark">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                                    <span class="small text-muted">ID: #{{ $detail->transaksi->kode_unik_pengambilan ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 border-bottom"><span class="fw-bold text-dark">{{ $detail->jumlah }}</span> <span class="text-muted">Unit</span></td>
                                        <td class="text-muted small py-3 border-bottom">{{ $detail->transaksi->waktu_pemesanan ? $detail->transaksi->waktu_pemesanan->format('d M Y') : '-' }}<br><span style="font-size: 0.75rem;">{{ $detail->transaksi->waktu_pemesanan ? $detail->transaksi->waktu_pemesanan->format('H:i') : '-' }}</span></td>
                                        <td class="py-3 border-bottom">
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-medium border border-success border-opacity-25">Selesai</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted border-bottom-0">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                                                <i class="fas fa-box-open text-muted fs-2"></i>
                                            </div>
                                            <p class="mb-0 fw-medium">Belum ada transaksi penjualan terbaru.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('styles')
<style>
    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .hover-card {
        transition: all 0.25s ease;
        border: 1px solid transparent;
    }

    .hover-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
        border-color: #e2e8f0;
    }

    .hover-card:hover .icon-box {
        transform: scale(1.05);
    }

    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
@endsection
