@extends('mitra.layouts.app')

@section('title', 'Marketplace Barter')

@section('content')
<div class="container-fluid px-4">
    {{-- Judul Halaman --}}
    <h1 class="mt-4">Marketplace Barter</h1>
    <p class="text-muted mb-4">Lihat produk dari mitra lain yang tersedia untuk dibarter.</p>

    <div class="mb-4" style="background-color: ">
        <a href="{{ route('mitra.barter.inbox') }}" class="btn btn-info">
            <i class="bi bi-inbox-fill"></i> Kotak Masuk Barter
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
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    {{-- Header Tabel --}}
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 80px;">Foto</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Mitra Pemilik</th>
                            <th scope="col">Harga Perkiraan</th>
                            <th scope="col" class="text-center">Stok</th>
                            <th scope="col" class="text-center" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    {{-- Isi Tabel --}}
                    <tbody>
                        @forelse ($produks as $produk)
                            <tr>
                                {{-- Foto Produk --}}
                                <td>
                                    @if($produk->foto_produk)
                                        <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        {{-- Placeholder Gambar --}}
                                        <div class="d-flex align-items-center justify-content-center bg-light" style="width: 60px; height: 60px; border-radius: 8px; color: #aaa;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    @endif
                                </td>

                                {{-- Nama Produk --}}
                                <td class="fw-bold">{{ $produk->nama_produk }}</td>

                                {{-- Mitra Pemilik --}}
                                <td class="text-muted">{{ $produk->mitra->nama_mitra ?? 'N/A' }}</td>

                                {{-- Harga & Stok --}}
                                <td>Rp {{ number_format($produk->harga_diskon) }}</td>
                                <td class="text-center">{{ $produk->stok_tersisa }}</td>

                                {{-- Tombol Aksi (dengan Logika Cooldown) --}}
                                <td class="text-center">
                                    @if($pendingOffers->contains($produk->produk_id))
                                        <button class="btn btn-secondary btn-sm" disabled data-bs-toggle="tooltip" title="Anda sudah mengajukan barter untuk produk ini.">
                                            Diajukan
                                        </button>
                                    @else
                                        <a href="{{ route('mitra.barter.create', $produk->produk_id) }}" class="btn btn-primary btn-sm">
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
