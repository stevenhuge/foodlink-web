@extends('mitra.layouts.app')
@section('title', 'Edit Produk: ' . $produk->nama_produk)
@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h1 class="h5 mb-0"><i class="fas fa-edit me-2"></i>Edit Produk: {{ $produk->nama_produk }}</h1>
    </div>
    <div class="card-body">

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

        <form action="{{ route('mitra.produk.update', $produk->produk_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Kolom Kiri: Detail Produk Dasar dan Harga --}}
                <div class="col-md-6">
                    <h6 class="mb-3 text-secondary border-bottom pb-2">Informasi Dasar</h6>

                    {{-- Nama Produk --}}
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label required">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk"
                            class="form-control @error('nama_produk') is-invalid @enderror"
                            value="{{ old('nama_produk', $produk->nama_produk) }}" required>
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
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->kategori_id }}" @if(old('kategori_id', $produk->kategori_id) == $kategori->kategori_id) selected @endif>
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
                            <option value="Jual-Cepat" @if(old('tipe_penawaran', $produk->tipe_penawaran) == 'Jual-Cepat') selected @endif>Jual Cepat (Surplus)</option>
                            <option value="Donasi" @if(old('tipe_penawaran', $produk->tipe_penawaran) == 'Donasi') selected @endif>Donasi (Gratis)</option>
                        </select>
                        @error('tipe_penawaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="mb-3 mt-4 text-secondary border-bottom pb-2">Harga & Stok</h6>
                    <div class="row">
                        {{-- Harga Normal --}}
                        <div class="col-md-6 mb-3">
                            <label for="harga_normal" class="form-label required">Harga Normal (Rp)</label>
                            <input type="number" id="harga_normal" name="harga_normal"
                                class="form-control @error('harga_normal') is-invalid @enderror"
                                value="{{ old('harga_normal', $produk->harga_normal) }}" min="0" required>
                            @error('harga_normal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Diskon/Jual --}}
                        <div class="col-md-6 mb-3">
                            <label for="harga_diskon" class="form-label required">Harga Jual / Diskon (Rp)</label>
                            <input type="number" id="harga_diskon" name="harga_diskon"
                                class="form-control @error('harga_diskon') is-invalid @enderror"
                                value="{{ old('harga_diskon', $produk->harga_diskon) }}" min="0" required>
                            @error('harga_diskon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Stok Awal --}}
                        <div class="col-md-6 mb-3">
                            <label for="stok_awal" class="form-label required">Stok Awal</label>
                            <input type="number" id="stok_awal" name="stok_awal"
                                class="form-control @error('stok_awal') is-invalid @enderror"
                                value="{{ old('stok_awal', $produk->stok_awal) }}" min="1" required>
                            @error('stok_awal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stok Tersisa --}}
                        <div class="col-md-6 mb-3">
                            <label for="stok_tersisa" class="form-label required">Stok Tersisa</label>
                            <input type="number" id="stok_tersisa" name="stok_tersisa"
                                class="form-control @error('stok_tersisa') is-invalid @enderror"
                                value="{{ old('stok_tersisa', $produk->stok_tersisa) }}" min="0" max="{{ $produk->stok_awal }}" required>
                            <div class="form-text">Tidak boleh melebihi Stok Awal.</div>
                            @error('stok_tersisa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status Produk --}}
                    <div class="mb-3">
                        <label for="status_produk" class="form-label required">Status Produk</label>
                        <select id="status_produk" name="status_produk"
                            class="form-select @error('status_produk') is-invalid @enderror" required>
                            <option value="Tersedia" @if(old('status_produk', $produk->status_produk) == 'Tersedia') selected @endif>Tersedia (Siap Jual)</option>
                            <option value="Habis" @if(old('status_produk', $produk->status_produk) == 'Habis') selected @endif>Habis (Stok 0)</option>
                            <option value="Ditarik" @if(old('status_produk', $produk->status_produk) == 'Ditarik') selected @endif>Ditarik (Jadikan Draft)</option>
                        </select>
                        <div class="form-text">Mengubah status produk langsung mempengaruhi ketersediaan di laman pembeli.</div>
                        @error('status_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- Kolom Kanan: Waktu dan Foto --}}
                <div class="col-md-6">
                    <h6 class="mb-3 text-secondary border-bottom pb-2">Pengaturan Waktu</h6>

                    {{-- Tanggal Kadaluarsa --}}
                    <div class="mb-3">
                        <label for="waktu_kadaluarsa" class="form-label required">Tanggal Kadaluarsa</label>
                        <input type="datetime-local" id="waktu_kadaluarsa" name="waktu_kadaluarsa"
                            class="form-control @error('waktu_kadaluarsa') is-invalid @enderror"
                            value="{{ old('waktu_kadaluarsa', $produk->waktu_kadaluarsa->format('Y-m-d\TH:i')) }}" required>
                        @error('waktu_kadaluarsa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Waktu Ambil Mulai --}}
                    <div class="mb-3">
                        <label for="waktu_ambil_mulai" class="form-label required">Waktu Ambil Mulai</label>
                        <input type="datetime-local" id="waktu_ambil_mulai" name="waktu_ambil_mulai"
                            class="form-control @error('waktu_ambil_mulai') is-invalid @enderror"
                            value="{{ old('waktu_ambil_mulai', $produk->waktu_ambil_mulai->format('Y-m-d\TH:i')) }}" required>
                        @error('waktu_ambil_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Waktu Ambil Selesai --}}
                    <div class="mb-4">
                        <label for="waktu_ambil_selesai" class="form-label required">Waktu Ambil Selesai</label>
                        <input type="datetime-local" id="waktu_ambil_selesai" name="waktu_ambil_selesai"
                            class="form-control @error('waktu_ambil_selesai') is-invalid @enderror"
                            value="{{ old('waktu_ambil_selesai', $produk->waktu_ambil_selesai->format('Y-m-d\TH:i')) }}" required>
                        @error('waktu_ambil_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <h6 class="mb-3 text-secondary border-bottom pb-2">Deskripsi & Foto</h6>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Jelaskan kondisi produk...">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div> {{-- End Row --}}

            <div class="row">
                <div class="col-md-6">
                    {{-- Foto Produk --}}
                    <div class="mb-4">
                        <label for="foto_produk" class="form-label">Foto Produk (Kosongkan jika tidak ingin ganti)</label>

                        @if($produk->foto_produk)
                            <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}"
                                class="img-thumbnail d-block mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <p class="text-muted small">(Belum ada foto)</p>
                        @endif

                        <input type="file" id="foto_produk" name="foto_produk"
                            class="form-control @error('foto_produk') is-invalid @enderror"
                            accept="image/*">
                        @error('foto_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-success btn-lg shadow-sm me-2">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('mitra.produk.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </form>

    </div>
</div>

@endsection
