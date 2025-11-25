@extends('mitra.layouts.auth')

@section('title', 'Registrasi Mitra')

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

    <form method="POST" action="{{ route('mitra.register') }}">
        @csrf

        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">Informasi Usaha</h6>

        {{-- Nama Usaha --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nama_mitra') is-invalid @enderror"
                   id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra') }}"
                   required autofocus placeholder="Nama Usaha">
            <label for="nama_mitra">Nama Usaha / Mitra</label>
        </div>

        {{-- Email --}}
        <div class="form-floating mb-3">
            <input type="email" class="form-control @error('email_bisnis') is-invalid @enderror"
                   id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}"
                   required placeholder="email@bisnis.com">
            <label for="email_bisnis">Email Bisnis</label>
        </div>

        {{-- No Telepon --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror"
                   id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                   required placeholder="08xxx">
            <label for="nomor_telepon">Nomor Telepon (PIC)</label>
        </div>

        {{-- Kategori Usaha --}}
        <div class="form-floating mb-3">
            <select class="form-select @error('kategori_usaha_id') is-invalid @enderror"
                    id="kategori_usaha_id" name="kategori_usaha_id" required>
                <option value="" selected disabled>Pilih Kategori...</option>
                @foreach($kategoriUsaha as $kategori)
                    <option value="{{ $kategori->kategori_usaha_id }}"
                        {{ old('kategori_usaha_id') == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
            <label for="kategori_usaha_id">Kategori Usaha</label>
        </div>

        {{-- Alamat --}}
        <div class="form-floating mb-3">
            <textarea class="form-control @error('alamat') is-invalid @enderror"
                      id="alamat" name="alamat" style="height: 80px"
                      required placeholder="Alamat">{{ old('alamat') }}</textarea>
            <label for="alamat">Alamat Lengkap</label>
        </div>

        {{-- Deskripsi --}}
        <div class="form-floating mb-4">
            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                      id="deskripsi" name="deskripsi" style="height: 80px"
                      placeholder="Deskripsi">{{ old('deskripsi') }}</textarea>
            <label for="deskripsi">Deskripsi Singkat (Opsional)</label>
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
            Sudah punya akun? <a href="{{ route('mitra.login') }}" class="fw-bold text-decoration-none">Login disini</a>
        </div>
    </form>

@endsection
