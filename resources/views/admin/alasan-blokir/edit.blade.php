{{-- resources/views/admin/alasan-blokir/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Alasan Blokir')

@section('content')

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Edit Alasan Blokir</h2>
    </div>

    <!-- Gunakan Grid untuk membatasi lebar form di layar besar -->
    <div class="row">
        <div class="col-lg-6">

            <!-- Card Utama untuk Form -->
            <div class="card border-0 shadow-sm rounded-3">

                {{-- PERBAIKAN: Gunakan $alasan_blokir, bukan $alasanBlokirOption --}}
                <form action="{{ route('admin.alasan-blokir.update', $alasan_blokir->alasan_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Card Header -->
                    <div class="card-header py-3">
                        <h5 class="mb-0 text-dark">Edit: {{ $alasan_blokir->alasan_text }}</h5>
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

                        <!-- Input Teks Alasan -->
                        <div class="mb-3">
                            <label for="alasan_text" class="form-label">Teks Alasan</label>
                            <input type="text"
                                   id="alasan_text"
                                   name="alasan_text"
                                   class="form-control @error('alasan_text') is-invalid @enderror"
                                   {{-- PERBAIKAN: Gunakan $alasan_blokir --}}
                                   value="{{ old('alasan_text', $alasan_blokir->alasan_text) }}"
                                   required
                                   autofocus>

                            @error('alasan_text')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div> <!-- End .card-body -->

                    <!-- Card Footer untuk Aksi -->
                    <div class="card-footer bg-light d-flex justify-content-between align-items-center">

                        <!-- Tombol Batal -->
                        <a href="{{ route('admin.alasan-blokir.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Batal
                        </a>

                        <!-- Tombol Simpan -->
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>

                    </div> <!-- End .card-footer -->

                </form>
            </div> <!-- End .card -->

        </div> <!-- End .col-lg-6 -->
    </div> <!-- End .row -->

@endsection
