@extends('admin.layouts.app')

@section('title', 'Audit Riwayat Barter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800 fw-bold">Audit Riwayat Barter</h2>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        
        <!-- Filter Form -->
        <form action="{{ route('admin.barter.index') }}" method="GET" class="row g-3 mb-4 align-items-end">
            <div class="col-md-3">
                <label for="status" class="form-label text-muted small fw-bold">Filter Status</label>
                <select name="status" id="status" class="form-select border-0 bg-light">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 fw-medium">
                    <i class="fas fa-filter me-1"></i> Terapkan Filter
                </button>
            </div>
            @if(request()->has('status') && request()->status != '')
            <div class="col-md-2">
                <a href="{{ route('admin.barter.index') }}" class="btn btn-light w-100 text-muted">
                    Reset
                </a>
            </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 text-uppercase text-secondary" style="font-size: 0.75rem;">ID Tagihan</th>
                        <th class="text-uppercase text-secondary" style="font-size: 0.75rem;">Aktor Pengaju</th>
                        <th class="text-uppercase text-secondary" style="font-size: 0.75rem;">Aktor Penerima</th>
                        <th class="text-uppercase text-secondary" style="font-size: 0.75rem;">Obyek Barter</th>
                        <th class="text-uppercase text-secondary" style="font-size: 0.75rem;">Tipe & Status</th>
                        <th class="text-uppercase text-secondary" style="font-size: 0.75rem;">Waktu</th>
                        <th class="text-end pe-3 text-uppercase text-secondary" style="font-size: 0.75rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barters as $b)
                        <tr>
                            <td class="ps-3 fw-medium text-dark">#BTR-{{ Str::padLeft($b->barter_id, 4, '0') }}</td>
                            
                            <!-- Pengaju -->
                            <td>
                                @if($b->tipe_barter == 'Mitra-User')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle mb-1">User</span><br>
                                    <span class="fw-medium text-dark">{{ $b->pengajuUser->nama_lengkap ?? 'User Tdk Diketahui' }}</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle mb-1">Mitra</span><br>
                                    <span class="fw-medium text-dark">{{ $b->pengajuMitra->nama_mitra ?? 'Mitra Tdk Diketahui' }}</span>
                                @endif
                            </td>

                            <!-- Penerima -->
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle mb-1">Mitra Tujuan</span><br>
                                <span class="fw-medium text-dark">{{ $b->penerimaMitra->nama_mitra ?? 'Mitra Tdk Diketahui' }}</span>
                            </td>

                            <!-- Obyek (Produk) -->
                            <td>
                                <div class="small">
                                    <span class="text-muted d-block" style="font-size: 0.7rem;"><i class="fas fa-hand-holding-heart me-1"></i> Diminta:</span>
                                    <span class="fw-medium text-dark">{{ $b->produkDiminta->nama_produk ?? 'Produk Dihapus' }}</span>
                                </div>
                                <div class="small mt-1">
                                    <span class="text-muted d-block" style="font-size: 0.7rem;"><i class="fas fa-exchange-alt me-1"></i> Ditawarkan:</span>
                                    <span class="fw-medium text-success">
                                        @if($b->produkDitawarkan)
                                            {{ $b->produkDitawarkan->nama_produk }}
                                        @elseif($b->nama_barang_manual)
                                            {{ $b->nama_barang_manual }} (Manual)
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Tipe & Status -->
                            <td>
                                <div class="mb-1 text-muted small fw-medium">{{ $b->tipe_barter }}</div>
                                @if($b->status_barter == 'Pending')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Pending</span>
                                @elseif($b->status_barter == 'Disetujui')
                                    <span class="badge bg-info text-dark px-2 py-1 rounded-pill">Disetujui</span>
                                @elseif($b->status_barter == 'Selesai')
                                    <span class="badge bg-success px-2 py-1 rounded-pill">Selesai</span>
                                @elseif($b->status_barter == 'Ditolak')
                                    <span class="badge bg-danger px-2 py-1 rounded-pill">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1 rounded-pill">{{ $b->status_barter }}</span>
                                @endif
                            </td>

                            <!-- Waktu -->
                            <td>
                                <span class="d-block small text-dark fw-medium">{{ \Carbon\Carbon::parse($b->waktu_pengajuan)->format('d M Y') }}</span>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($b->waktu_pengajuan)->format('H:i') }} WIB</span>
                            </td>

                            <!-- Aksi -->
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.barter.show', $b->barter_id) }}" class="btn btn-sm btn-light border shadow-sm text-primary rounded-circle" data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-3"><i class="fas fa-search fa-3x opacity-25"></i></div>
                                <h5 class="text-dark fw-bold">Belum Ada Riwayat Barter</h5>
                                <p class="text-secondary small">Transaksi barter antar ekosistem akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-end mt-4">
            {{ $barters->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
