@extends('admin.layouts.app')

@section('title', 'Detail Audit Barter #BTR-' . Str::padLeft($barter->barter_id, 4, '0'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.barter.index') }}" class="btn btn-light shadow-sm text-secondary rounded-circle" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="h3 mb-0 text-gray-800 fw-bold">Audit Detail Barter</h2>
    </div>
    
    <!-- Status Badge -->
    <div>
        @if($barter->status_barter == 'Pending')
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6">Pending</span>
        @elseif($barter->status_barter == 'Disetujui')
            <span class="badge bg-info text-dark px-3 py-2 rounded-pill fs-6">Disetujui</span>
        @elseif($barter->status_barter == 'Selesai')
            <span class="badge bg-success px-3 py-2 rounded-pill fs-6">Selesai</span>
        @elseif($barter->status_barter == 'Ditolak')
            <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">Ditolak</span>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- PIHAK PENGAJU -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.8rem; letter-spacing: 0.5px;">Pihak 1 (Pengaju)</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    @if($barter->tipe_barter == 'Mitra-User')
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ substr($barter->pengajuUser->nama_lengkap ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">{{ $barter->pengajuUser->nama_lengkap ?? 'User Tdk Diketahui' }}</h5>
                            <span class="badge bg-primary mt-1">Pengguna Umum</span>
                            <div class="text-muted small mt-1"><i class="fas fa-envelope me-1"></i> {{ $barter->pengajuUser->email ?? '-' }}</div>
                        </div>
                    @else
                        @if($barter->pengajuMitra && $barter->pengajuMitra->logo_mitra)
                            <img src="{{ asset($barter->pengajuMitra->logo_mitra) }}" alt="Logo" class="rounded-circle object-fit-cover shadow-sm bg-white" style="width: 50px; height: 50px;">
                        @else
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                {{ substr($barter->pengajuMitra->nama_mitra ?? 'M', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">{{ $barter->pengajuMitra->nama_mitra ?? 'Mitra Tdk Diketahui' }}</h5>
                            <span class="badge bg-success mt-1">Mitra FoodLink</span>
                            <div class="text-muted small mt-1"><i class="fas fa-phone me-1"></i> {{ $barter->pengajuMitra->nomor_telepon ?? '-' }}</div>
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-dark mb-2">Barang yang Ditawarkan:</h6>
                    @if($barter->produkDitawarkan)
                        <!-- OPSI 1: Produk Sistem -->
                        <div class="border rounded-3 p-3">
                            <span class="badge bg-light text-dark border mb-2"><i class="fas fa-box"></i> Produk Terdaftar di Sistem</span>
                            <div class="fw-medium text-dark">{{ $barter->produkDitawarkan->nama_produk }}</div>
                            <div class="text-muted small">Kuantitas: {{ $barter->jumlah_ditawarkan }} / Sisa Stok di database: {{ $barter->produkDitawarkan->stok }}</div>
                        </div>
                    @elseif($barter->nama_barang_manual)
                        <!-- OPSI 2: Barang Manual -->
                        <div class="border rounded-3 p-3 bg-warning bg-opacity-10 border-warning border-opacity-25">
                            <span class="badge bg-warning text-dark mb-2"><i class="fas fa-hand-holding-box"></i> Barang Manual Luar Sistem</span>
                            <div class="fw-bold text-dark mb-1">{{ $barter->nama_barang_manual }}</div>
                            <p class="text-muted small mb-2">{{ $barter->deskripsi_barang_manual }}</p>
                            
                            <div class="d-flex gap-2 mt-2">
                                @if($barter->foto_barang_manual)
                                    <a href="{{ asset('storage/' . $barter->foto_barang_manual) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-image me-1"></i> Lihat Foto Barang
                                    </a>
                                @endif
                                @if($barter->bukti_struk)
                                    <a href="{{ asset('storage/' . $barter->bukti_struk) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-receipt me-1"></i> Lihat Bukti Pembelian
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-danger small"><i class="fas fa-exclamation-triangle"></i> Data barang ditawarkan tidak ditemukan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- PIHAK PENERIMA -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.8rem; letter-spacing: 0.5px;">Pihak 2 (Penerima)</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    @if($barter->penerimaMitra && $barter->penerimaMitra->logo_mitra)
                        <img src="{{ asset($barter->penerimaMitra->logo_mitra) }}" alt="Logo" class="rounded-circle object-fit-cover shadow-sm bg-white" style="width: 50px; height: 50px;">
                    @else
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ substr($barter->penerimaMitra->nama_mitra ?? 'M', 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">{{ $barter->penerimaMitra->nama_mitra ?? 'Mitra Tujuan Tdk Diketahui' }}</h5>
                        <span class="badge bg-success mt-1">Mitra FoodLink</span>
                        <div class="text-muted small mt-1"><i class="fas fa-store me-1"></i> {{ $barter->penerimaMitra->alamat ?? '-' }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-dark mb-2">Barang yang Diminta:</h6>
                    @if($barter->produkDiminta)
                        <div class="border rounded-3 p-3">
                            <span class="badge bg-light text-dark border mb-2"><i class="fas fa-box"></i> Produk Target di Sistem</span>
                            <div class="d-flex align-items-center gap-3">
                                @if($barter->produkDiminta->foto_produk)
                                    <img src="{{ asset('storage/' . $barter->produkDiminta->foto_produk) }}" alt="Foto" class="rounded-2 object-fit-cover" style="width: 60px; height: 60px;">
                                @else
                                    <div class="rounded-2 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 60px;">
                                        <i class="fas fa-image fs-4"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $barter->produkDiminta->nama_produk }}</div>
                                    <div class="text-muted small">ID: {{ $barter->produkDiminta->produk_id }}</div>
                                    <div class="text-fl-green fw-medium small mt-1">Rp {{ number_format($barter->produkDiminta->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-danger small"><i class="fas fa-exclamation-triangle"></i> Produk target tidak ditemukan. Kemungkinan telah dihapus.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- TIMELINE/LOG -->
    <div class="col-12 mt-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.8rem; letter-spacing: 0.5px;">Log Audit & Timeline</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="fas fa-clock text-info me-2"></i> 
                        <span class="fw-medium text-dark">Waktu Pengajuan:</span> 
                        <span class="text-muted">{{ \Carbon\Carbon::parse($barter->waktu_pengajuan)->translatedFormat('l, d F Y H:i:s') }}</span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-info-circle text-secondary me-2"></i>
                        <span class="fw-medium text-dark">Tipe Barter System:</span>
                        <span class="text-muted">{{ $barter->tipe_barter }}</span>
                    </li>
                    <li>
                        <i class="fas fa-flag text-primary me-2"></i>
                        <span class="fw-medium text-dark">Status Final:</span>
                        <span class="text-muted">{{ $barter->status_barter }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
