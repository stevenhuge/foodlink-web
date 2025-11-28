@extends('mitra.layouts.auth')

@section('title', 'Sanggahan Akun')

@section('content')
    {{-- Header Judul --}}
    <div class="text-center mb-4">
        <h4 class="fw-bold text-primary mb-1">
            <i class="fas fa-file-shield me-2"></i>Sanggahan Akun
        </h4>
        <p class="text-muted small">
            Formulir pengajuan peninjauan kembali akun yang diblokir.
        </p>
    </div>

    {{-- Alert Error Global --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Success (Opsional) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('mitra.blokir.public.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Input Email (Floating Label) --}}
        <div class="form-floating mb-3">
            <input type="email"
                   name="email_bisnis"
                   class="form-control @error('email_bisnis') is-invalid @enderror"
                   id="email_bisnis"
                   placeholder="name@example.com"
                   value="{{ old('email_bisnis') }}"
                   required>
            <label for="email_bisnis">Email Bisnis Terdaftar</label>

            @error('email_bisnis')
                <div class="invalid-feedback ps-2">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Alasan (Floating Label) --}}
        <div class="form-floating mb-3">
            <textarea name="alasan_sanggah"
                      class="form-control @error('alasan_sanggah') is-invalid @enderror"
                      id="alasan_sanggah"
                      placeholder="Jelaskan alasan..."
                      style="height: 120px"
                      required>{{ old('alasan_sanggah') }}</textarea>
            <label for="alasan_sanggah">Jelaskan kronologi & alasan sanggahan</label>

            @error('alasan_sanggah')
                <div class="invalid-feedback ps-2">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input File --}}
        <div class="mb-4">
            <label for="bukti_files" class="form-label fw-semibold small text-secondary mb-1">
                Bukti Pendukung (Wajib)
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary"><i class="fas fa-upload"></i></span>
                <input type="file"
                       name="bukti_files[]"
                       class="form-control @error('bukti_files') is-invalid @enderror"
                       id="bukti_files"
                       multiple
                       required
                       accept=".jpg,.jpeg,.png,.pdf">
            </div>

            {{-- Error Spesifik File --}}
            @error('bukti_files')
                <div class="text-danger small mt-1 ps-1"><i class="fas fa-info-circle me-1"></i>{{ $message }}</div>
            @enderror
            @error('bukti_files.*')
                <div class="text-danger small mt-1 ps-1"><i class="fas fa-info-circle me-1"></i>Format file salah atau terlalu besar.</div>
            @enderror

            <div class="form-text small mt-1 text-muted">
                <ul class="mb-0 ps-3">
                    <li>Format: JPG, PNG, PDF (Maks. 2MB/file).</li>
                    <li>Bisa upload lebih dari 1 file sekaligus.</li>
                </ul>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-paper-plane me-2"></i>Kirim Sanggahan
            </button>

            <a href="{{ route('mitra.login') }}" class="btn btn-light text-muted btn-sm mt-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
            </a>
        </div>
    </form>
@endsection
