@extends('mitra.layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="text-center mb-4">
        <h5 class="fw-bold">Lupa Password?</h5>
        <p class="text-muted small">Masukkan email bisnis Anda, kami akan mengirimkan link untuk mereset password.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('mitra.password.email') }}">
        @csrf
        <div class="form-floating mb-4">
            <input type="email" class="form-control @error('email_bisnis') is-invalid @enderror"
                   id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}"
                   required placeholder="Email">
            <label for="email_bisnis">Email Bisnis</label>
            @error('email_bisnis')
                <div class="invalid-feedback small">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
            <a href="{{ route('mitra.login') }}" class="btn btn-light border">Kembali ke Login</a>
        </div>
    </form>
@endsection
