@extends('mitra.layouts.app') {{-- Pastikan nama layout Anda benar --}}
@section('title', 'Edit Profil Saya')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Profil Usaha</h1>
    <p class="text-muted mb-4">Perbarui informasi usaha dan detail akun Anda.</p>

    {{-- Tampilkan Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-lg" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
            <strong>Oops! Ada kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('mitra.profile.update') }}" method="POST">
        @csrf
        @method('PATCH') {{-- Gunakan PATCH karena kita update data --}}

        <div class="row">
            {{-- Kolom Informasi Usaha --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0">Informasi Usaha</h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- Input Nama Usaha --}}
                        <div class="mb-3">
                            <label for="nama_mitra" class="form-label">Nama Usaha / Mitra</label>
                            <input type="text" id="nama_mitra" name="nama_mitra" class="form-control @error('nama_mitra') is-invalid @enderror" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required>
                            @error('nama_mitra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Email (Readonly) --}}
                        <div class="mb-3">
                            <label class="form-label">Email Bisnis (Tidak dapat diubah)</label>
                            <input type="email" class="form-control" value="{{ $mitra->email_bisnis }}" disabled readonly>
                        </div>

                        {{-- Input No Telp --}}
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon (PIC)</label>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" value="{{ old('nomor_telepon', $mitra->nomor_telepon) }}" required>
                            @error('nomor_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Alamat --}}
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap Usaha</label>
                            <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $mitra->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Deskripsi --}}
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Singkat Usaha</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $mitra->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dropdown Kategori --}}
                        <div class="mb-3">
                            <label for="kategori_usaha_id" class="form-label">Kategori Usaha</label>
                            <select id="kategori_usaha_id" name="kategori_usaha_id" class="form-select @error('kategori_usaha_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                {{-- Pastikan variabel $kategoriUsaha dikirim dari controller --}}
                                @isset($kategoriUsaha)
                                    @foreach($kategoriUsaha as $kategori)
                                        <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_usaha_id', $mitra->kategori_usaha_id) == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('kategori_usaha_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Ubah Password --}}
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0">Ubah Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small">Kosongkan semua field di bawah ini jika Anda tidak ingin mengubah password.</p>

                        {{-- Input Password Lama --}}
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Password Baru --}}
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Konfirmasi Password Baru --}}
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Simpan Utama --}}
        <div class="text-end mt-4 mb-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-save me-2"></i>Simpan Perubahan Profil
            </button>
        </div>
    </form>
</div>
@endsection
