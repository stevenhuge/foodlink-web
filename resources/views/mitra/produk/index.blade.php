@extends('mitra.layouts.app')
@section('title', 'Manajemen Produk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
<h1><i class="fas fa-cubes me-2 text-primary"></i>Manajemen Produk</h1>
{{-- Tombol Tambah Produk menggunakan kelas Bootstrap --}}
<a href="{{ route('mitra.produk.create') }}" class="btn btn-primary shadow-sm">
<i class="fas fa-plus-circle me-2"></i> Tambah Produk Baru (Draft)
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

<div class="card shadow-sm">
<div class="card-body p-0">
{{-- Menggunakan table Bootstrap dengan hover, responsif, dan header light --}}
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead class="table-light">
<tr>
<th scope="col" style="width: 80px;">Foto</th>
<th scope="col">Nama Produk</th>
<th scope="col">Harga</th>
<th scope="col">Stok Tersisa</th>
<th scope="col" style="width: 100px;">Status</th>
<th scope="col" style="width: 200px;">Aksi</th>
</tr>
</thead>
<tbody>
@forelse ($produks as $produk)
{{-- Memberi highlight abu-abu untuk produk yang Ditarik/Draft --}}
<tr class="@if($produk->status_produk == 'Ditarik') table-secondary @endif">
<td>
@if($produk->foto_produk)
<img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}"
class="rounded img-fluid" style="width: 60px; height: 60px; object-fit: cover;">
@else
<span class="text-muted small">(No-img)</span>
@endif
</td>
<td>
<strong>{{ $produk->nama_produk }}</strong>




<small class="text-muted">
<i class="fas fa-tag"></i> Tipe: {{ $produk->tipe_penawaran }}
</small>
</td>
<td>
@if($produk->tipe_penawaran == 'Donasi')
<span class="badge bg-info text-dark fw-bold">GRATIS</span>
@else
<span class="text-danger fw-bold">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</span>
@if($produk->harga_normal > $produk->harga_diskon)




<small class="text-muted text-decoration-line-through">Rp {{ number_format($produk->harga_normal, 0, ',', '.') }}</small>
@endif
@endif
</td>
<td>
@php
$stock_percent = ($produk->stok_awal > 0) ? ($produk->stok_tersisa / $produk->stok_awal) * 100 : 0;
$stock_bg = ($stock_percent > 50) ? 'bg-success' : (($stock_percent > 10) ? 'bg-warning' : 'bg-danger');
@endphp
<div class="d-flex flex-column">
<span class="fw-bold @if($produk->stok_tersisa > 0) text-dark @else text-danger @endif mb-1">
{{ $produk->stok_tersisa }} <small class="text-muted">/ {{ $produk->stok_awal }}</small>
</span>
@if($produk->status_produk == 'Tersedia' || $produk->status_produk == 'Ditarik')
<div class="progress" style="height: 6px;">
<div class="progress-bar {{ $stock_bg }}" role="progressbar" style="width: {{ $stock_percent }}%" aria-valuenow="{{ $produk->stok_tersisa }}" aria-valuemin="0" aria-valuemax="{{ $produk->stok_awal }}"></div>
</div>
@endif
</div>
</td>

                        <td>
                            @php
                                $badge_class = '';
                                $badge_icon = '';
                                switch ($produk->status_produk) {
                                    case 'Tersedia': $badge_class = 'bg-success'; $badge_icon = 'check-circle'; break;
                                    case 'Habis': $badge_class = 'bg-danger'; $badge_icon = 'times-circle'; break;
                                    case 'Ditarik': $badge_class = 'bg-secondary'; $badge_icon = 'archive'; break;
                                    default: $badge_class = 'bg-warning text-dark'; $badge_icon = 'exclamation-triangle'; break;
                                }
                            @endphp
                            <span class="badge {{ $badge_class }}">
                                <i class="fas fa-{{ $badge_icon }} me-1"></i> {{ $produk->status_produk }}
                            </span>
                            @if($produk->status_produk == 'Ditarik')
                                <small class="d-block text-muted">(Draft)</small>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex flex-column gap-1">
                                {{-- Tombol Edit selalu ada --}}
                                <a href="{{ route('mitra.produk.edit', $produk->produk_id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>

                                @if($produk->status_produk == 'Tersedia')
                                    {{-- Tombol Unpublish --}}
                                    <form action="{{ route('mitra.produk.unpublish', $produk->produk_id) }}" method="POST" class="d-grid w-100">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-warning text-dark">
                                            <i class="fas fa-arrow-down me-1"></i> Jadikan Draft
                                        </button>
                                    </form>
                                @elseif($produk->status_produk == 'Ditarik')
                                    {{-- Tombol Publish --}}
                                    <form action="{{ route('mitra.produk.publish', $produk->produk_id) }}" method="POST" class="d-grid w-100">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-upload me-1"></i> Publish
                                        </button>
                                    </form>
                                @else
                                    <small class="text-muted fst-italic pt-1">(Tidak ada aksi cepat)</small>
                                @endif

                                {{-- Tombol Delete --}}
                                <form action="{{ route('mitra.produk.destroy', $produk->produk_id) }}" method="POST" class="d-grid w-100 mt-2 border-top pt-1" onsubmit="return confirm('Anda yakin ingin menghapus produk ini? Tindakan ini TIDAK dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
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
