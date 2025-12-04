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

    <div class="card border-0 shadow-sm mb-5 overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-8 p-4">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                            <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Akun Anda Siap Digunakan</h5>
                    <p class="text-muted mb-0">Anda dapat mulai mengelola produk surplus, melakukan barter, atau memantau penjualan Anda sekarang.</p>
                </div>
                <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-4">
                    <i class="bi bi-shop text-primary opacity-25" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-5">
        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Akses Cepat</h5>
        <div class="row g-3">

            {{-- Menu 1: Tambah Produk --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.produk.create') }}" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-primary-subtle text-primary mx-auto mb-3">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Tambah Produk</h6>
                        <p class="text-muted small mb-0">Jual / Donasi</p>
                    </div>
                </a>
            </div>

            {{-- Menu 2: Kelola Produk --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.produk.index') }}" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-info-subtle text-info mx-auto mb-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Produk Saya</h6>
                        <p class="text-muted small mb-0">Kelola Stok</p>
                    </div>
                </a>
            </div>

            {{-- Menu 3: Barter --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.barter.index') }}" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-warning-subtle text-warning mx-auto mb-3">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Barter Market</h6>
                        <p class="text-muted small mb-0">Tukar Produk</p>
                    </div>
                </a>
            </div>

            {{-- Menu 4: Inbox --}}
            <div class="col-6 col-md-3">
                <a href="{{ route('mitra.barter.inbox') }}" class="card h-100 border-0 shadow-sm hover-card text-decoration-none">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box bg-danger-subtle text-danger mx-auto mb-3">
                            <i class="bi bi-envelope"></i>
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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                    <h5 class="fw-bold mb-0">Penjualan Terakhir</h5>
                    <a href="{{ route('mitra.riwayat.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penjualanTerbaru as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-3 text-center" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-bag-check text-primary"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold d-block text-dark">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                                    <span class="small text-muted">ID: #{{ $detail->transaksi->kode_unik_pengambilan }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="fw-bold">{{ $detail->jumlah }}</span> Unit</td>
                                        <td class="text-muted small">{{ $detail->transaksi->waktu_pemesanan->format('d M Y, H:i') }}</td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2">Selesai</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="Empty" style="width: 60px; opacity: 0.5;" class="mb-3">
                                            <p class="mb-0">Belum ada transaksi penjualan terbaru.</p>
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
    /* Custom Styles for Dashboard */
    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s ease;
    }

    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
