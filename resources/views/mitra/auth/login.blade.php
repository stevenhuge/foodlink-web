@extends('mitra.layouts.app')

@section('title', 'Login Mitra')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            {{-- Card untuk form login --}}
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-bold my-4">Login Mitra</h3>
                </div>
                <div class="card-body p-4 p-sm-5">

                    {{-- Tampilkan error validasi atau error login --}}
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                         <div class="alert alert-danger" role="alert">
                             Email atau password yang Anda masukkan salah.
                         </div>
                    @endif

                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('mitra.login') }}">
                        @csrf

                        {{-- Input Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email_bisnis') is-invalid @enderror" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}" required autofocus placeholder="nama@bisnis.com">
                            <label for="email_bisnis">Email Bisnis</label>
                            @error('email_bisnis')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Input Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password">
                            <label for="password">Password</label>
                             @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                    </form>
                </div>
                {{-- <div class="card-footer text-center py-3">
                    <div class="small"><a href="#">Belum punya akun? Daftar!</a></div>
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
