@extends('mitra.layouts.app')

@section('title', 'Inbox Barter')

@section('content')
<div class="container-fluid px-4">
    {{-- Judul Halaman --}}
    <h1 class="mt-4">Inbox Barter</h1>
    <p class="text-muted mb-4">Kelola tawaran barter yang Anda kirim dan terima.</p>

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

    <!-- ============================================= -->
    <!-- Bagian 1: Tawaran Diterima (Masuk) -->
    <!-- ============================================= -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-light border-0">
            <h5 class="mb-0">Tawaran Diterima (Masuk)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-4">Dari Mitra</th>
                            <th scope="col" class="py-3 px-4">Barang Ditawarkan</th>
                            <th scope="col" class="py-3 px-4">Meminta Produk Anda</th>
                            <th scope="col" class="py-3 px-4">Status</th>
                            <th scope="col" class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tawaranDiterima as $tawaran)
                        <tr>
                            <td class="py-3 px-4">{{ $tawaran->pengajuMitra->nama_mitra ?? 'N/A' }}</td>

                            {{-- === Logika Tampilan Tawaran (Opsi 1 vs 2) === --}}
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    {{-- Foto --}}
                                    @php $foto_url = null; @endphp
                                    @if($tawaran->produk_ditawarkan_id && $tawaran->produkDitawarkan && $tawaran->produkDitawarkan->foto_produk)
                                        @php $foto_url = Storage::url($tawaran->produkDitawarkan->foto_produk); @endphp
                                    @elseif($tawaran->foto_barang_manual)
                                        @php $foto_url = Storage::url($tawaran->foto_barang_manual); @endphp
                                    @endif

                                    @if($foto_url)
                                        <img src="{{ $foto_url }}" alt="Foto Tawaran" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3">
                                    @else
                                        {{-- Placeholder Gambar --}}
                                        <div class="d-flex align-items-center justify-content-center bg-light me-3" style="width: 60px; height: 60px; border-radius: 8px; color: #aaa;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    @endif

                                    {{-- Info --}}
                                    <div>
                                        @if($tawaran->produk_ditawarkan_id && $tawaran->produkDitawarkan)
                                            <div class="fw-bold">{{ $tawaran->produkDitawarkan->nama_produk ?? 'N/A' }}</div>
                                            <span class="badge bg-primary-soft text-primary">Produk</span>
                                            <small class="text-muted ms-1">Jumlah: {{ $tawaran->jumlah_ditawarkan ?? 1 }}</small>
                                        @else
                                            <div class="fw-bold">{{ $tawaran->nama_barang_manual }}</div>
                                            <span class="badge bg-success-soft text-success">Manual</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- ========================================== --}}

                            <td class="py-3 px-4">{{ $tawaran->produkDiminta->nama_produk ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                @if($tawaran->status_barter == 'Diajukan')
                                    <span class="badge bg-warning text-dark">{{ $tawaran->status_barter }}</span>
                                @elseif($tawaran->status_barter == 'Diterima')
                                    <span class="badge bg-success">{{ $tawaran->status_barter }}</span>
                                @elseif($tawaran->status_barter == 'Ditolak')
                                    <span class="badge bg-danger">{{ $tawaran->status_barter }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $tawaran->status_barter }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($tawaran->status_barter == 'Diajukan')
                                    <div class="d-flex justify-content-center" style="gap: 5px;">
                                        <form action="{{ route('mitra.barter.accept', $tawaran->barter_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                        </form>
                                        <form action="{{ route('mitra.barter.reject', $tawaran->barter_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox mb-2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-7l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>
                                <p class="mb-0">Tidak ada tawaran barter yang masuk.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================= -->
    <!-- Bagian 2: Tawaran Terkirim (Keluar) -->
    <!-- ============================================= -->
    <div class="card shadow-sm border-0 rounded-lg mb-4">
        <div class="card-header bg-light border-0">
            <h5 class="mb-0">Tawaran Terkirim (Keluar)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="py-3 px-4">Ke Mitra</th>
                            <th scope="col" class="py-3 px-4">Meminta Produk</th>
                            <th scope="col" class="py-3 px-4">Barang Anda Tawarkan</th>
                            <th scope="col" class="py-3 px-4">Status</th>
                            <th scope="col" class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tawaranTerkirim as $tawaran)
                        <tr>
                            <td class="py-3 px-4">{{ $tawaran->penerimaMitra->nama_mitra ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $tawaran->produkDiminta->nama_produk ?? 'N/A' }}</td>

                            {{-- === Logika Tampilan Tawaran (Opsi 1 vs 2) === --}}
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    {{-- Foto --}}
                                    @php $foto_url = null; @endphp
                                    @if($tawaran->produk_ditawarkan_id && $tawaran->produkDitawarkan && $tawaran->produkDitawarkan->foto_produk)
                                        @php $foto_url = Storage::url($tawaran->produkDitawarkan->foto_produk); @endphp
                                    @elseif($tawaran->foto_barang_manual)
                                        @php $foto_url = Storage::url($tawaran->foto_barang_manual); @endphp
                                    @endif

                                    @if($foto_url)
                                        <img src="{{ $foto_url }}" alt="Foto Tawaran" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3">
                                    @else
                                        {{-- Placeholder Gambar --}}
                                        <div class="d-flex align-items-center justify-content-center bg-light me-3" style="width: 60px; height: 60px; border-radius: 8px; color: #aaa;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                        </div>
                                    @endif

                                    {{-- Info --}}
                                    <div>
                                        @if($tawaran->produk_ditawarkan_id && $tawaran->produkDitawarkan)
                                            <div class="fw-bold">{{ $tawaran->produkDitawarkan->nama_produk ?? 'N/A' }}</div>
                                            <span class="badge bg-primary-soft text-primary">Produk</span>
                                            <small class="text-muted ms-1">Jumlah: {{ $tawaran->jumlah_ditawarkan ?? 1 }}</small>
                                        @else
                                            <div class="fw-bold">{{ $tawaran->nama_barang_manual }}</div>
                                            <span class="badge bg-success-soft text-success">Manual</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- ========================================== --}}

                            <td class="py-3 px-4">
                                @if($tawaran->status_barter == 'Diajukan')
                                    <span class="badge bg-warning text-dark">{{ $tawaran->status_barter }}</span>
                                @elseif($tawaran->status_barter == 'Diterima')
                                    <span class="badge bg-success">{{ $tawaran->status_barter }}</span>
                                @elseif($tawaran->status_barter == 'Ditolak')
                                    <span class="badge bg-danger">{{ $tawaran->status_barter }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $tawaran->status_barter }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($tawaran->status_barter == 'Diajukan')
                                    <form action="{{ route('mitra.barter.cancel', $tawaran->barter_id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-secondary btn-sm">Batalkan</button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send mb-2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                <p class="mb-0">Anda belum mengirim tawaran barter.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
