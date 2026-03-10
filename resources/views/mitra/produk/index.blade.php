@extends('mitra.layouts.app')
@section('title', 'Manajemen Produk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Manajemen Produk</h3>
    {{-- Tombol Tambah Produk menggunakan kelas Bootstrap --}}
    <a href="{{ route('mitra.produk.create') }}" class="btn btn-primary shadow-sm rounded-pill fw-medium px-4">
        <i class="fas fa-plus-circle me-2"></i> Tambah Produk
    </a>
</div>

{{-- Pesan Sukses menggunakan alert Bootstrap --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
<i class="fas fa-check-circle me-2"></i>
{{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 mb-4">
    <div class="card-body p-0">
        {{-- Menggunakan table Bootstrap dengan hover, responsif, dan header light --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 border-0">
                <thead class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="background-color: transparent;">
                    <tr>
                        <th class="ps-4 text-muted font-weight-bold border-bottom py-3" style="width: 80px;">Foto</th>
                        <th class="text-muted font-weight-bold border-bottom py-3">Nama Produk</th>
                        <th class="text-muted font-weight-bold border-bottom py-3">Harga</th>
                        <th class="text-muted font-weight-bold border-bottom py-3">Stok Tersisa</th>
                        <th class="text-muted font-weight-bold border-bottom py-3" style="width: 120px;">Status</th>
                        <th class="text-muted font-weight-bold border-bottom py-3" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produks as $produk)
                        {{-- Memberi highlight abu-abu untuk produk yang Ditarik/Draft --}}
                        <tr class="@if($produk->status_produk == 'Ditarik') table-active bg-opacity-10 @endif">
                            <td class="ps-4 py-3 border-bottom">
                                @if($produk->foto_produk)
                                    <img src="{{ str_starts_with($produk->foto_produk, 'data:image') ? $produk->foto_produk : Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}"
                                        class="rounded img-fluid border" style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted border-secondary-subtle" style="width: 56px; height: 56px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 border-bottom">
                                <span class="fw-bold text-dark d-block" style="font-size: 1rem;">{{ $produk->nama_produk }}</span>
                                <small class="text-muted bg-light rounded px-2 py-1 mt-1 d-inline-block border">
                                    <i class="fas fa-tag me-1"></i> {{ $produk->tipe_penawaran }}
                                </small>
                            </td>
                            <td class="py-3 border-bottom">
                                @if($produk->tipe_penawaran == 'Donasi')
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-2 fw-bold">GRATIS</span>
                                @else
                                    <span class="text-dark fw-bold d-block">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</span>
                                    @if($produk->harga_normal > $produk->harga_diskon)
                                        <small class="text-muted text-decoration-line-through">Rp {{ number_format($produk->harga_normal, 0, ',', '.') }}</small>
                                    @endif
                                @endif
                            </td>
                            <td class="py-3 border-bottom">
                                @php
                                    $stock_percent = ($produk->stok_awal > 0) ? ($produk->stok_tersisa / $produk->stok_awal) * 100 : 0;
                                    $stock_bg = ($stock_percent > 50) ? 'bg-success' : (($stock_percent > 10) ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="d-flex flex-column pe-4">
                                    <span class="fw-bold @if($produk->stok_tersisa > 0) text-dark @else text-danger @endif mb-1">
                                        {{ $produk->stok_tersisa }} <small class="text-muted fw-normal">/ {{ $produk->stok_awal }}</small>
                                    </span>
                                    @if($produk->status_produk == 'Tersedia' || $produk->status_produk == 'Ditarik')
                                        <div class="progress rounded-pill bg-light" style="height: 6px;">
                                            <div class="progress-bar rounded-pill {{ $stock_bg }}" role="progressbar" style="width: {{ $stock_percent }}%" aria-valuenow="{{ $produk->stok_tersisa }}" aria-valuemin="0" aria-valuemax="{{ $produk->stok_awal }}"></div>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3 border-bottom">
                                @php
                                    $badge_class = '';
                                    switch ($produk->status_produk) {
                                        case 'Tersedia': $badge_class = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; break;
                                        case 'Habis': $badge_class = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; break;
                                        case 'Ditarik': $badge_class = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'; break;
                                        default: $badge_class = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badge_class }} rounded-pill px-3 py-2 fw-medium">
                                    {{ $produk->status_produk }}
                                </span>
                            </td>

                            <td class="py-3 border-bottom">
                                <div class="d-flex gap-2 flex-wrap">
                                    {{-- Tombol Edit selalu ada --}}
                                    <a href="{{ route('mitra.produk.edit', $produk->produk_id) }}" class="btn btn-sm btn-light border text-secondary" data-bs-toggle="tooltip" title="Edit Produk">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($produk->status_produk == 'Tersedia')
                                        {{-- Tombol Unpublish --}}
                                        <form action="{{ route('mitra.produk.unpublish', $produk->produk_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light border text-warning" data-bs-toggle="tooltip" title="Tarik ke Draft">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                        </form>
                                    @elseif($produk->status_produk == 'Ditarik')
                                        {{-- Tombol Publish --}}
                                        <form action="{{ route('mitra.produk.publish', $produk->produk_id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light border text-success" data-bs-toggle="tooltip" title="Publish Produk">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('mitra.produk.destroy', $produk->produk_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
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
                            <i class="fas fa-inbox fa-4x mb-3 text-secondary"></i>
                            <p class="mb-3 fs-5">Anda belum memiliki produk yang terdaftar.</p>
                            <a href="{{ route('mitra.produk.create') }}" class="btn btn-lg btn-primary shadow-sm">
                                <i class="fas fa-plus me-2"></i> Mulai Buat Produk Pertama Anda
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
