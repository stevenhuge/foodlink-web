@extends('admin.layouts.app')
{{-- Pastikan ini menunjuk ke layout admin Anda yang benar --}}

@section('title', 'Detail Mitra: ' . $mitra->nama_mitra)

@section('content')

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Detail Mitra</h2>
    </div>

    <!-- Card Detail Mitra -->
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Card Header -->
        <div class="card-header py-3">
            <h5 class="mb-0 text-dark">{{ $mitra->nama_mitra }}</h5>
        </div>

        <!-- Card Body -->
        <div class="card-body">

            <!-- Description List untuk Data Mitra -->
            <dl class="row">

                <dt class="col-sm-3 text-muted">ID Mitra</dt>
                <dd class="col-sm-9">{{ $mitra->mitra_id }}</dd>

                <dt class="col-sm-3 text-muted">Email Bisnis</dt>
                <dd class="col-sm-9">{{ $mitra->email_bisnis }}</dd>

                <dt class="col-sm-3 text-muted">Kategori Usaha</dt>
                <dd class="col-sm-9">{{ $mitra->kategoriUsaha->nama_kategori ?? '-' }}</dd>

                <dt class="col-sm-3 text-muted">Nomor Telepon</dt>
                <dd class="col-sm-9">{{ $mitra->nomor_telepon }}</dd>

                <dt class="col-sm-3 text-muted">Alamat</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $mitra->alamat }}</dd>

                <dt class="col-sm-3 text-muted">Deskripsi</dt>
                <dd class="col-sm-9">{{ $mitra->deskripsi ?? '-' }}</dd>

                <hr class="my-3">

                <dt class="col-sm-3 text-muted">Status Verifikasi</dt>
                <dd class="col-sm-9">
                    @if($mitra->status_verifikasi == 'Pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($mitra->status_verifikasi == 'Disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($mitra->status_verifikasi == 'Ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">{{ $mitra->status_verifikasi }}</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">Status Akun</dt>
                <dd class="col-sm-9">
                    <span class="badge {{ $mitra->status_akun == 'Diblokir' ? 'bg-danger' : 'bg-success' }}">
                        {{ $mitra->status_akun }}
                    </span>
                    @if($mitra->status_akun == 'Diblokir' && $m->alasanBlokir)
                        <small class="d-block text-danger mt-1" style="cursor: help;" title="Alasan: {{ $m->alasanBlokir->alasan_text }}">
                            <i class="fas fa-info-circle me-1"></i>{{ $m->alasanBlokir->alasan_text }}
                        </small>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">Tanggal Daftar</dt>
                <dd class="col-sm-9">{{ $mitra->created_at->format('d M Y, H:i') }}</dd>

            </dl> <!-- End .row (Description List) -->

        </div> <!-- End .card-body -->

        <!-- Card Footer untuk Aksi -->
        <div class="card-footer bg-light d-flex justify-content-between align-items-center">

            <!-- Tombol Kembali -->
            <a href="{{ route('admin.mitra.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>

            <!-- Tombol Aksi (Edit, dll.) -->
            <div>
                @if(auth()->guard('admin')->user()->role === 'SuperAdmin')
                    <a href="{{ route('admin.mitra.edit', $mitra->mitra_id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i> Edit Mitra Ini
                    </a>
                @endif

                {{-- Anda bisa menambahkan tombol aksi lain di sini jika perlu --}}
                {{-- Contoh: Tombol Blokir/Aktifkan jika ingin ada di halaman detail juga --}}

            </div>
        </div> <!-- End .card-footer -->

    </div> <!-- End .card -->

@endsection
