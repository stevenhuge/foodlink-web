@extends('user.layouts.auth')

@section('title', 'Registrasi Pengguna')

@section('content')

    {{-- Flash Error Message --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('user.register') }}">
        @csrf

        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">Informasi Pribadi</h6>

        {{-- Nama Lengkap --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                   id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                   required autofocus placeholder="Nama Lengkap">
            <label for="nama_lengkap">Nama Lengkap</label>
        </div>

        <div class="row g-3 mb-3">
            {{-- Jenis Kelamin --}}
            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror"
                            id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="" selected disabled>Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                </div>
            </div>
            
            {{-- No Telepon --}}
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror"
                           id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                           required placeholder="08xxx">
                    <label for="nomor_telepon">Nomor Telepon</label>
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div class="form-floating mb-4">
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}"
                   required placeholder="email@domain.com">
            <label for="email">Email</label>
        </div>

        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">Keamanan Akun</h6>

        {{-- Password --}}
        <div class="form-floating mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required placeholder="Password">
            <label for="password">Password</label>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="form-floating mb-4">
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation"
                   required placeholder="Konfirmasi Password">
            <label for="password_confirmation">Ulangi Password</label>
        </div>

        {{-- Tombol Submit --}}
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane me-2"></i> Daftar Sekarang
            </button>
        </div>

        <div class="text-center small border-top pt-3">
            Sudah punya akun? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="fw-bold text-decoration-none smooth-transition">Gunakan Login di Chat AI</a>
        </div>
    </form>

@endsection
