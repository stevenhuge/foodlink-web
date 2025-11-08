@extends('admin.layouts.app')
{{-- Pastikan ini menunjuk ke layout admin Anda yang benar --}}

@section('title', 'Edit Mitra: ' . $mitra->nama_mitra)

@section('content')

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Edit Mitra: {{ $mitra->nama_mitra }}</h2>
    </div>

    <!-- Card Utama untuk Form -->
    <div class="card border-0 shadow-sm rounded-3">

        <form action="{{ route('admin.mitra.update', $mitra->mitra_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Card Body -->
            <div class="card-body">

                <!-- Tampilkan error validasi umum jika ada -->
                @if ($errors->any() && !$errors->has('password_baru'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Oops! Ada kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                @if(!Str::contains($error, 'password'))
                                    <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Layout Grid 2 Kolom -->
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- Nama Mitra -->
                        <div class="mb-3">
                            <label for="nama_mitra" class="form-label">Nama Mitra</label>
                            <input type="text" id="nama_mitra" name="nama_mitra" class="form-control @error('nama_mitra') is-invalid @enderror" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required>
                            @error('nama_mitra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Bisnis -->
                        <div class="mb-3">
                            <label for="email_bisnis" class="form-label">Email Bisnis</label>
                            <input type="email" id="email_bisnis" name="email_bisnis" class="form-control @error('email_bisnis') is-invalid @enderror" value="{{ old('email_bisnis', $mitra->email_bisnis) }}" required>
                            @error('email_bisnis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" value="{{ old('nomor_telepon', $mitra->nomor_telepon) }}" required>
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Kategori Usaha -->
                        <div class="mb-3">
                            <label for="kategori_usaha_id" class="form-label">Kategori Usaha</label>
                            <select id="kategori_usaha_id" name="kategori_usaha_id" class="form-select @error('kategori_usaha_id') is-invalid @enderror">
                                <option value="">-- Tidak Ada Kategori --</option>
                                @foreach($kategoriUsaha as $kategori)
                                    <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_usaha_id', $mitra->kategori_usaha_id) == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_usaha_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $mitra->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $mitra->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div> <!-- End .row -->

                <hr class="my-4">

                <!-- Bagian Reset Password -->
                <h5 class="mb-3">Reset Password (Opsional)</h5>
                <p class="text-muted">Biarkan kosong jika Anda tidak ingin mengubah password Mitra.</p>

                @if ($errors->has('password_baru'))
                    <div class="alert alert-danger py-2" role="alert">
                        <i class="fas fa-times-circle me-1"></i> {{ $errors->first('password_baru') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <!-- Password Baru -->
                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru</label>
                            <input type="password" id="password_baru" name="password_baru" class="form-control @error('password_baru') is-invalid @enderror">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Konfirmasi Password Baru -->
                        <div class="mb-3">
                            <label for="password_baru_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" id="password_baru_confirmation" name="password_baru_confirmation" class="form-control @error('password_baru') is-invalid @enderror">
                        </div>
                    </div>
                </div>

            </div> <!-- End .card-body -->

            <!-- Card Footer untuk Aksi -->
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">

                <!-- Tombol Batal/Kembali -->
                <a href="{{ route('admin.mitra.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Batal
                </a>

                <!-- Tombol Simpan -->
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>

            </div> <!-- End .card-footer -->

        </form>
    </div> <!-- End .card -->

@endsection
