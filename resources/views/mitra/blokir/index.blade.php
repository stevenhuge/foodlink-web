@extends('mitra.layouts.app')

@section('title', 'Form Penyanggahan Akun')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-file-signature me-2"></i> Form Penyanggahan Akun</h4>
                </div>
                <div class="card-body p-4">

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Gunakan formulir ini jika akun Anda diblokir dan Anda merasa ini adalah kekeliruan.
                        Mohon isi data dengan sebenar-benarnya.
                    </p>

                    <form action="{{ route('mitra.blokir.public.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Input Email Wajib untuk Identifikasi --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Bisnis Anda <span class="text-danger">*</span></label>
                            <input type="email" name="email_bisnis" class="form-control" required placeholder="contoh: restoran@email.com" value="{{ old('email_bisnis') }}">
                            <div class="form-text">Masukkan email yang terdaftar di akun Mitra Anda.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Sanggahan <span class="text-danger">*</span></label>
                            <textarea name="alasan_sanggah" class="form-control" rows="5" required placeholder="Jelaskan kronologi atau alasan mengapa akun Anda harus dipulihkan...">{{ old('alasan_sanggah') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bukti Pendukung (Foto/Dokumen) <span class="text-danger">*</span></label>
                            <input type="file" name="bukti_files[]" class="form-control" multiple required accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text">
                                - Format: JPG, PNG, PDF.<br>
                                - Maksimal 2MB per file.<br>
                                - Anda dapat memilih lebih dari 1 file sekaligus.
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('mitra.login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-paper-plane me-2"></i> Kirim Sanggahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
