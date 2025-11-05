@extends('mitra.layouts.app')
@section('title', 'Tambah Produk Baru')
@section('content')
    <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h1 class="h5 mb-0"><i class="fas fa-box-open me-2"></i>Tambah Produk Baru</h1>
    </div>
    <div class="card-body">

        {{-- Bagian ini sudah dihandle oleh layout utama, namun tetap diletakkan di sini sebagai fallback/contoh --}}
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Oops! Ada kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('mitra.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Kolom Kiri untuk Detail Produk Dasar --}}
                <div class="col-md-6">

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label required">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk"
                            class="form-control @error('nama_produk') is-invalid @enderror"
                            value="{{ old('nama_produk') }}" placeholder="Contoh: Roti Tawar Gandum" required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori Produk --}}
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label required">Kategori Produk</label>
                        <select id="kategori_id" name="kategori_id"
                            class="form-select @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            {{-- Looping ini perlu disesuaikan dengan environment Anda --}}
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id') == $kategori->kategori_id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tipe Penawaran --}}
                    <div class="mb-3">
                        <label for="tipe_penawaran" class="form-label required">Tipe Penawaran</label>
                        <select id="tipe_penawaran" name="tipe_penawaran"
                            class="form-select @error('tipe_penawaran') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Jual-Cepat" {{ old('tipe_penawaran') == 'Jual-Cepat' ? 'selected' : '' }}>Jual Cepat (Surplus)</option>
                            <option value="Donasi" {{ old('tipe_penawaran') == 'Donasi' ? 'selected' : '' }}>Donasi (Gratis)</option>
                        </select>
                        <div class="form-text">Pilih 'Jual Cepat' untuk produk diskon, atau 'Donasi' untuk produk gratis.</div>
                        @error('tipe_penawaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Stok Awal --}}
                    <div class="mb-3">
                        <label for="stok_awal" class="form-label required">Stok Awal</label>
                        <input type="number" id="stok_awal" name="stok_awal"
                            class="form-control @error('stok_awal') is-invalid @enderror"
                            value="{{ old('stok_awal', 1) }}" min="1" required>
                        @error('stok_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Kolom Kanan untuk Harga & Waktu --}}
                <div class="col-md-6">

                    <h6 class="mb-3 text-secondary">Pengaturan Harga</h6>
                    <div class="row">
                        {{-- Harga Normal --}}
                        <div class="col-md-6 mb-3">
                            <label for="harga_normal" class="form-label required">Harga Normal (Rp)</label>
                            <input type="number" id="harga_normal" name="harga_normal"
                                class="form-control @error('harga_normal') is-invalid @enderror"
                                value="{{ old('harga_normal', 0) }}" min="0" required>
                            <div class="form-text">Isi 0 jika tipe penawaran adalah Donasi.</div>
                            @error('harga_normal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Jual / Diskon --}}
                        <div class="col-md-6 mb-3">
                            <label for="harga_diskon" class="form-label required">Harga Jual / Diskon (Rp)</label>
                            <input type="number" id="harga_diskon" name="harga_diskon"
                                class="form-control @error('harga_diskon') is-invalid @enderror"
                                value="{{ old('harga_diskon', 0) }}" min="0" required>
                            <div class="form-text">Harga akhir yang harus dibayar konsumen.</div>
                            @error('harga_diskon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="mb-3 text-secondary">Pengaturan Waktu</h6>
                    <div class="row">
                        {{-- Tanggal Kadaluarsa --}}
                        <div class="col-md-12 mb-3">
                            <label for="waktu_kadaluarsa" class="form-label required">Tanggal Kadaluarsa</label>
                            <input type="datetime-local" id="waktu_kadaluarsa" name="waktu_kadaluarsa"
                                class="form-control @error('waktu_kadaluarsa') is-invalid @enderror"
                                value="{{ old('waktu_kadaluarsa') }}" required>
                            @error('waktu_kadaluarsa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Waktu Ambil Mulai --}}
                        <div class="col-md-6 mb-3">
                            <label for="waktu_ambil_mulai" class="form-label required">Waktu Ambil Mulai</label>
                            <input type="datetime-local" id="waktu_ambil_mulai" name="waktu_ambil_mulai"
                                class="form-control @error('waktu_ambil_mulai') is-invalid @enderror"
                                value="{{ old('waktu_ambil_mulai') }}" required>
                            @error('waktu_ambil_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Waktu Ambil Selesai --}}
                        <div class="col-md-6 mb-3">
                            <label for="waktu_ambil_selesai" class="form-label required">Waktu Ambil Selesai</label>
                            <input type="datetime-local" id="waktu_ambil_selesai" name="waktu_ambil_selesai"
                                class="form-control @error('waktu_ambil_selesai') is-invalid @enderror"
                                value="{{ old('waktu_ambil_selesai') }}" required>
                            @error('waktu_ambil_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div> {{-- End Row --}}

            {{-- Deskripsi dan Foto di bawah --}}
            <div class="row">
                <div class="col-md-6">
                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Jelaskan kondisi, bahan, atau informasi penting lainnya...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    {{-- Foto Produk --}}
                    <div class="mb-3">
                        <label for="foto_produk" class="form-label">Foto Produk</label>
                        <input type="file" id="foto_produk" name="foto_produk"
                            class="form-control @error('foto_produk') is-invalid @enderror"
                            accept="image/*">
                        <div class="form-text">Pilih gambar terbaik untuk produk Anda. (Maks 2MB)</div>
                        @error('foto_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-success btn-lg shadow-sm">
                    <i class="fas fa-save me-2"></i> Simpan Produk Baru
                </button>
                <a href="{{ route('mitra.dashboard') }}" class="btn btn-outline-secondary btn-lg ms-2">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
