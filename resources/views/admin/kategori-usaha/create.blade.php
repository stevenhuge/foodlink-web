{{-- resources/views/admin/kategori-usaha/create.blade.php --}}
@extends('admin.layouts.app')
{{-- Pastikan ini menunjuk ke layout admin Anda yang benar --}}

@section('title', 'Tambah Kategori Usaha Baru')

@section('content')

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Tambah Kategori Baru</h2>
    </div>

    <!-- Gunakan Grid untuk membatasi lebar form di layar besar -->
    <div class="row">
        <div class="col-lg-6">

            <!-- Card Utama untuk Form -->
            <div class="card border-0 shadow-sm rounded-3">

                <form action="{{ route('admin.kategori-usaha.store') }}" method="POST">
                    @csrf

                    <!-- Card Header -->
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-dark">Detail Kategori</h5>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">

                        <!-- Tampilkan error validasi jika ada -->
                        @if ($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Oops! Ada kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Input Nama Kategori -->
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text"
                                id="nama_kategori"
                                name="nama_kategori"
                                class="form-control @error('nama_kategori') is-invalid @enderror"
                                value="{{ old('nama_kategori') }}"
                                required
                                autofocus>

                            @error('nama_kategori')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div> <!-- End .card-body -->

                    <!-- Card Footer untuk Aksi -->
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">

                        <!-- Tombol Batal -->
                        <a href="{{ route('admin.kategori-usaha.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>

                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Simpan Kategori
                        </button>

                    </div> <!-- End .card-footer -->

                </form>
            </div> <!-- End .card -->

        </div> <!-- End .col-lg-6 -->
    </div> <!-- End .row -->

@endsection
