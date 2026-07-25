@extends('admin.layouts.app')

@section('title', 'Edit Anggota Tim')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Edit Anggota Tim</h2>
        <a href="{{ route('admin.anggota-tim.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.anggota-tim.update', $anggotaTim->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $anggotaTim->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label fw-bold">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $anggotaTim->jabatan) }}" required>
                    @error('jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="foto_url" class="form-label fw-bold">URL Foto (Link Gambar)</label>
                    <input type="url" class="form-control @error('foto_url') is-invalid @enderror" id="foto_url" name="foto_url" value="{{ old('foto_url', $anggotaTim->foto_url) }}" placeholder="https://example.com/foto.jpg">
                    <small class="text-muted">Masukkan link langsung ke gambar foto (opsional).</small>
                    @error('foto_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                @if($anggotaTim->foto_url)
                <div class="mb-4">
                    <p class="fw-bold mb-2">Preview Foto Saat Ini:</p>
                    <img src="{{ $anggotaTim->foto_url }}" alt="Preview" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                @endif

                <hr class="mb-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.anggota-tim.index') }}" class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
