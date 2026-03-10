@extends('mitra.layouts.app')

@section('title', 'Marketplace Barter')

@section('content')
<div class="container-fluid px-3">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">Marketplace Barter</h3>
            <p class="text-muted mb-0 mt-1">Cari dan temukan produk menarik untuk dipertukarkan.</p>
        </div>
        <a href="{{ route('mitra.barter.inbox') }}" class="btn btn-light border shadow-sm rounded-pill fw-medium text-dark px-4">
            <i class="fas fa-inbox me-2 text-primary"></i> Kotak Masuk
        </a>
    </div>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-lg" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Konten Utama (Tabel dalam Card) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    {{-- Header Tabel --}}
                    <thead class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="background-color: transparent;">
                        <tr>
                            <th class="ps-4 text-muted font-weight-bold border-bottom py-3" style="width: 80px;">Foto</th>
                            <th class="text-muted font-weight-bold border-bottom py-3">Nama Produk</th>
                            <th class="text-muted font-weight-bold border-bottom py-3">Mitra Pemilik</th>
                            <th class="text-muted font-weight-bold border-bottom py-3">Harga Est.</th>
                            <th class="text-center text-muted font-weight-bold border-bottom py-3">Stok</th>
                            <th class="text-center text-muted font-weight-bold border-bottom py-3" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    {{-- Isi Tabel --}}
                    <tbody>
                        @forelse ($produks as $produk)
                            <tr>
                                {{-- Foto Produk --}}
                                <td class="ps-4 py-3 border-bottom">
                                    @if($produk->foto_produk)
                                        <img src="{{ str_starts_with($produk->foto_produk, 'data:image') ? $produk->foto_produk : Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center text-muted border-secondary-subtle" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Nama Produk --}}
                                <td class="py-3 border-bottom">
                                    <span class="fw-bold text-dark d-block mb-1">{{ $produk->nama_produk }}</span>
                                    <small class="text-muted bg-light rounded px-2 py-1 border"><i class="fas fa-tag me-1"></i>{{ $produk->tipe_penawaran }}</small>
                                </td>

                                {{-- Mitra Pemilik --}}
                                <td class="text-muted py-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-store text-secondary me-2"></i> {{ $produk->mitra->nama_mitra ?? 'Unknown' }}
                                    </div>
                                </td>

                                {{-- Harga & Stok --}}
                                <td class="py-3 border-bottom fw-bold text-dark">Rp {{ number_format($produk->harga_diskon, 0, ',', '.') }}</td>
                                <td class="text-center py-3 border-bottom"><span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium fs-6">{{ $produk->stok_tersisa }}</span></td>

                                {{-- Tombol Aksi (dengan Logika Cooldown) --}}
                                <td class="text-center py-3 border-bottom">
                                    @if($pendingOffers->contains($produk->produk_id))
                                        <button class="btn btn-light border text-secondary btn-sm rounded-pill px-3 fw-medium w-100" disabled data-bs-toggle="tooltip" title="Anda sudah mengajukan barter untuk produk ini.">
                                            <i class="fas fa-clock me-1"></i> Diajukan
                                        </button>
                                    @else
                                        <a href="{{ route('mitra.barter.create', $produk->produk_id) }}" class="btn btn-primary btn-sm rounded-pill shadow-sm px-3 fw-medium w-100">
                                            Ajukan Barter
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            {{-- Pesan "Kosong" --}}
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-archive mb-2"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
                                    <p class="mb-0">Tidak ada produk dari mitra lain yang tersedia untuk barter saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Catatan: Pastikan layout 'mitra.layouts.app' memuat Bootstrap JS --}}
{{-- agar tooltip pada tombol 'Diajukan' dapat berfungsi. --}}
@endsection
