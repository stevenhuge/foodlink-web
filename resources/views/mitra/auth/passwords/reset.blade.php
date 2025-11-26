@extends('mitra.layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <div class="text-center mb-4">
        <h5 class="fw-bold">Buat Password Baru</h5>
        <p class="text-muted small">Silakan masukkan password baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('mitra.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-floating mb-3">
            <input type="email" class="form-control @error('email_bisnis') is-invalid @enderror"
                   id="email_bisnis" name="email_bisnis" value="{{ $email_bisnis ?? old('email_bisnis') }}"
                   required readonly placeholder="Email">
            <label for="email_bisnis">Email Bisnis</label>
            @error('email_bisnis')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required placeholder="Password Baru" autofocus>
            <label for="password">Password Baru</label>
            @error('password')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-floating mb-4">
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Password">
            <label for="password_confirmation">Ulangi Password Baru</label>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
    </form>
@endsection
