@extends('mitra.layouts.auth')

@section('title', 'Login Mitra')

@section('content')

    {{-- Alert Error --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> Email atau password salah.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error_html'))
        <div class="alert alert-warning alert-dismissible fade show small border-0 bg-warning-subtle text-dark" role="alert">
            <i class="fas fa-ban me-2"></i>
            <div class="mt-1">
                {!! session('error_html') !!}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Login --}}
    <form method="POST" action="{{ route('mitra.login') }}">
        @csrf

        {{-- Input Email --}}
        <div class="form-floating mb-3">
            <input type="email" class="form-control @error('email_bisnis') is-invalid @enderror"
                   id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}"
                   required autofocus placeholder="nama@bisnis.com">
            <label for="email_bisnis">Email Bisnis</label>
            @error('email_bisnis')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Input Password --}}
        <div class="form-floating mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required placeholder="Password">
            <label for="password">Password</label>
            @error('password')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
            </div>
            {{-- Opsi: Link Lupa Password (jika ada fiturnya) --}}
            {{-- <a href="#" class="small text-decoration-none">Lupa Password?</a> --}}
        </div>

        {{-- Tombol Submit --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">
                Masuk Sekarang <i class="fas fa-arrow-right ms-2 small"></i>
            </button>
        </div>

        <div class="text-center mt-4 pt-2 border-top">
            <p class="small text-muted mb-0">Belum menjadi mitra?</p>
            <a href="{{ route('mitra.register') }}" class="fw-bold text-decoration-none">Daftar disini</a>
        </div>
    </form>

@endsection
