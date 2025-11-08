@extends('mitra.layouts.app')

@section('title', 'Registrasi Mitra')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            {{-- Card untuk form registrasi --}}
            <div class="card shadow-lg border-0 rounded-lg mt-5 mb-5">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="fw-bold my-4">Bergabung Menjadi Mitra Foodlink</h3>
                </div>
                <div class="card-body p-4 p-sm-5">
                    <p class="text-muted text-center mb-4">Daftarkan usaha Anda untuk membantu mengurangi food waste.</p>

                    {{-- Tampilkan error validasi jika ada --}}
                    @if ($errors->any())
                        <div class2="alert alert-danger rounded-lg" role="alert">
                            <strong>Oops! Ada kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('mitra.register') }}">
                        @csrf

                        <h5 class="mb-3 text-primary"><i class="fas fa-store me-2"></i>Informasi Usaha</h5>

                        {{-- Input Nama Usaha --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('nama_mitra') is-invalid @enderror" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra') }}" required autofocus placeholder="Nama Usaha Anda">
                            <label for="nama_mitra">Nama Usaha / Mitra</label>
                            @error('nama_mitra') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Email --}}
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control @error('email_bisnis') is-invalid @enderror" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}" required placeholder="email@bisnis.com">
                            <label for="email_bisnis">Email Bisnis</label>
                            @error('email_bisnis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input No Telepon --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required placeholder="08123456789">
                            <label for="nomor_telepon">Nomor Telepon (PIC)</label>
                            @error('nomor_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Kategori Usaha --}}
                        <div class="form-floating mb-3">
                             <select class="form-select @error('kategori_usaha_id') is-invalid @enderror" id="kategori_usaha_id" name="kategori_usaha_id" required>
                                <option value="">-- Pilih Kategori Usaha Anda --</option>
                                {{-- Loop data kategori yang dikirim dari controller --}}
                                @foreach($kategoriUsaha as $kategori)
                                    <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_usaha_id') == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="kategori_usaha_id">Kategori Usaha</label>
                            @error('kategori_usaha_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Alamat --}}
                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" style="height: 100px;" required placeholder="Alamat Lengkap Usaha">{{ old('alamat') }}</textarea>
                            <label for="alamat">Alamat Lengkap Usaha</label>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div class="form-floating mb-3">
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" style="height: 100px;" placeholder="Deskripsi Singkat Usaha">{{ old('deskripsi') }}</textarea>
                            <label for="deskripsi">Deskripsi Singkat Usaha (Opsional)</label>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3 text-primary"><i class="fas fa-lock me-2"></i>Informasi Akun</h5>

                        {{-- Input Password --}}
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Buat Password">
                            <label for="password">Password</label>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Input Konfirmasi Password --}}
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Konfirmasi Password">
                            <label for="password_confirmation">Konfirmasi Password</label>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light border-0">
                    <div class="small">
                        Sudah punya akun? <a href="{{ route('mitra.login') }}">Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
