@extends('mitra.layouts.app')
@section('title', 'Manajemen Voucher')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Voucher</h3>
    <a href="{{ route('mitra.voucher.create') }}" class="btn btn-primary shadow-sm rounded-pill fw-medium px-4">
        <i class="fas fa-plus-circle me-2"></i> Tambah Voucher
    </a>
</div>

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-0">
                <thead class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="background-color: transparent;">
                    <tr>
                        <th class="ps-4 text-muted font-weight-bold border-bottom py-3">Nama & Kode</th>
                        <th class="text-muted font-weight-bold border-bottom py-3">Potongan</th>
                        <th class="text-muted font-weight-bold border-bottom py-3">Alokasi</th>
                        <th class="text-muted font-weight-bold border-bottom py-3">Masa Berlaku</th>
                        <th class="text-muted font-weight-bold border-bottom py-3" style="width: 120px;">Status</th>
                        <th class="text-muted font-weight-bold border-bottom py-3" style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vouchers as $voucher)
                        <tr>
                            <td class="ps-4 py-3 border-bottom">
                                <span class="fw-bold text-dark d-block" style="font-size: 1rem;">{{ $voucher->nama_voucher }}</span>
                                <small class="text-muted bg-light rounded px-2 py-1 mt-1 d-inline-block border text-uppercase font-monospace fw-bold">
                                    <i class="fas fa-ticket-alt me-1"></i> {{ $voucher->kode_voucher }}
                                </small>
                            </td>
                            <td class="py-3 border-bottom">
                                @if($voucher->tipe_potongan === 'persentase')
                                    <span class="text-dark fw-bold d-block">{{ (int)$voucher->nilai_potongan }}%</span>
                                @else
                                    <span class="text-dark fw-bold d-block">Rp {{ number_format($voucher->nilai_potongan, 0, ',', '.') }}</span>
                                @endif
                                @if($voucher->minimal_belanja > 0)
                                    <small class="text-muted">Min: Rp {{ number_format($voucher->minimal_belanja, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td class="py-3 border-bottom">
                                @if($voucher->alokasi === 'semua_menu')
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1">Seluruh Menu</span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-1">Menu Tertentu</span>
                                    <small class="d-block mt-1 text-muted">{{ $voucher->produk->nama_produk ?? 'Produk dihapus' }}</small>
                                @endif
                            </td>
                            <td class="py-3 border-bottom">
                                <span class="d-block mb-1"><i class="fas fa-calendar-alt text-muted me-1"></i> {{ \Carbon\Carbon::parse($voucher->tanggal_mulai)->format('d M Y, H:i') }}</span>
                                <span><i class="fas fa-calendar-times text-muted me-1"></i> {{ \Carbon\Carbon::parse($voucher->tanggal_selesai)->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="py-3 border-bottom">
                                @if($voucher->status === 'aktif')
                                    @if(now() > $voucher->tanggal_selesai)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-2 fw-medium">Expired</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-medium">Aktif</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-2 fw-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3 border-bottom">
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('mitra.voucher.edit', $voucher->id) }}" class="btn btn-sm btn-light border text-secondary" data-bs-toggle="tooltip" title="Edit Voucher">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('mitra.voucher.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus voucher ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" data-bs-toggle="tooltip" title="Hapus Permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-ticket-alt fa-4x mb-3 text-secondary"></i>
                                <p class="mb-3 fs-5">Anda belum memiliki voucher promosi.</p>
                                <a href="{{ route('mitra.voucher.create') }}" class="btn btn-lg btn-primary shadow-sm">
                                    <i class="fas fa-plus me-2"></i> Buat Voucher Pertama Anda
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
