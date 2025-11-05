@extends('mitra.layouts.app')

@section('title', 'Ajukan Barter')

@section('content')
<div class="container-fluid px-4">
    {{-- Judul Halaman dan Tombol Kembali --}}
    <h1 class="mt-4">Ajukan Penawaran Barter</h1>
    <a href="{{ route('mitra.barter.index') }}" class="btn btn-link text-decoration-none px-0 mb-3">
        &larr; Kembali ke Marketplace
    </a>

    <div class="row">
        {{-- Kolom Kiri: Produk yang Diminta --}}
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">Produk yang Anda Minta</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        {{-- Foto --}}
                        @if($produk->foto_produk)
                            <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;" class="me-3">
                        @else
                            {{-- Placeholder Gambar --}}
                            <div class="d-flex align-items-center justify-content-center bg-light me-3" style="width: 80px; height: 80px; border-radius: 8px; color: #aaa;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                        @endif
                        {{-- Info --}}
                        <div>
                            <h6 class="fw-bold mb-1">{{ $produk->nama_produk }}</h6>
                            <div class="text-muted small mb-1">Oleh: {{ $produk->mitra->nama_mitra ?? 'N/A' }}</div>
                            <div class="fw-bold mb-1">Rp {{ number_format($produk->harga_diskon) }}</div>
                            <span class="badge bg-secondary-soft text-secondary">Stok: {{ $produk->stok_tersisa }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Penawaran --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">Detail Tawaran Anda</h5>
                </div>
                <div class="card-body p-4">
                    {{-- Form Penawaran --}}
                    <form action="{{ route('mitra.barter.store', $produk->produk_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Tampilkan Error Validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <strong class="d-block">Oops! Ada kesalahan:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Tampilkan Error Session --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Pilihan Opsi 1 vs Opsi 2 --}}
                        <div class="mb-3">
                            <label for="tipe_tawaran" class="form-label fw-bold">Pilih Opsi Tawaran Anda:</label>
                            <select id="tipe_tawaran" name="tipe_tawaran" class="form-select form-select-lg" required onchange="toggleOfferType()">
                                <option value="">-- Pilih Cara Menawar --</option>
                                <option value="existing" {{ old('tipe_tawaran') == 'existing' ? 'selected' : '' }}>1. Tawarkan dari Produk Saya yang Ada</option>
                                <option value="manual" {{ old('tipe_tawaran') == 'manual' ? 'selected' : '' }}>2. Tawarkan Barang Lain (Input Manual)</option>
                            </select>
                        </div>

                        {{-- Form untuk OPSI 1 (Existing) --}}
                        <div id="form-existing" class="border border-primary-soft rounded-3 p-3 mt-3" style="display:none;">
                            {{-- Dropdown Produk --}}
                            <div class="mb-3">
                                <label for="produk_ditawarkan_id" class="form-label">Pilih Produk Anda yang Tersedia:</label>
                                <select id="produk_ditawarkan_id" name="produk_ditawarkan_id" class="form-select" onchange="updateMaxQuantity()">
                                    <option value="">-- Pilih Produk dari Daftar Anda --</option>
                                    @foreach($produkPribadi as $p)
                                        <option value="{{ $p->produk_id }}" data-stok="{{ $p->stok_tersisa }}" {{ old('produk_ditawarkan_id') == $p->produk_id ? 'selected' : '' }}>
                                            {{ $p->nama_produk }} (Stok: {{ $p->stok_tersisa }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Input Kuantitas --}}
                            <div>
                                <label for="jumlah_ditawarkan" class="form-label">Jumlah yang Ditawarkan:</label>
                                <input type="number" id="jumlah_ditawarkan" name="jumlah_ditawarkan" value="{{ old('jumlah_ditawarkan', 1) }}" min="1" max="1" class="form-control" style="width: 120px;">
                                <div id="stok-info" class="form-text"></div>
                            </div>
                        </div>

                        {{-- Form untuk OPSI 2 (Manual) --}}
                        <div id="form-manual" class="border border-success-soft rounded-3 p-3 mt-3" style="display:none;">
                            <p class="form-text">Anda akan menawarkan barang baru. Silakan isi datanya seperti menambah produk.</p>

                            <div class="mb-3">
                                <label for="nama_barang_manual" class="form-label">Nama Barang</label>
                                <input type="text" id="nama_barang_manual" name="nama_barang_manual" value="{{ old('nama_barang_manual') }}" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="kategori_id_manual" class="form-label">Kategori Barang</label>
                                <select id="kategori_id_manual" name="kategori_id" class="form-select">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_id') == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="harga_perkiraan" class="form-label">Perkiraan Harga (Rp)</label>
                                <input type="number" id="harga_perkiraan" name="harga_perkiraan" value="{{ old('harga_perkiraan', 0) }}" min="0" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_barang_manual" class="form-label">Deskripsi Barang</label>
                                <textarea id="deskripsi_barang_manual" name="deskripsi_barang_manual" rows="4" class="form-control">{{ old('deskripsi_barang_manual') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="foto_barang_manual" class="form-label">Foto Barang (Wajib, Max 1MB)</label>
                                <input type="file" id="foto_barang_manual" name="foto_barang_manual" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="bukti_struk" class="form-label">Bukti Struk (Opsional, Max 1MB)</label>
                                <input type="file" id="bukti_struk" name="bukti_struk" class="form-control">
                            </div>
                        </div>

                        {{-- Tombol Kirim --}}
                        <div class="mt-4 d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Kirim Penawaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript (Tidak berubah, hanya perlu ada) --}}
<script>
    function toggleOfferType() {
        var type = document.getElementById('tipe_tawaran').value;
        var formExisting = document.getElementById('form-existing');
        var formManual = document.getElementById('form-manual');

        // Ambil semua elemen input di dalam form
        var produkSelect = document.getElementById('produk_ditawarkan_id');
        var jumlahInput = document.getElementById('jumlah_ditawarkan');
        var namaManual = document.getElementById('nama_barang_manual');
        var kategoriManual = document.getElementById('kategori_id_manual');
        var hargaManual = document.getElementById('harga_perkiraan');
        var deskripsiManual = document.getElementById('deskripsi_barang_manual');
        var fotoManual = document.getElementById('foto_barang_manual');

        // Reset status 'required'
        if (produkSelect) produkSelect.required = false;
        if (jumlahInput) jumlahInput.required = false;
        if (namaManual) namaManual.required = false;
        if (kategoriManual) kategoriManual.required = false;
        if (hargaManual) hargaManual.required = false;
        if (deskripsiManual) deskripsiManual.required = false;
        if (fotoManual) fotoManual.required = false;

        // Atur tampilan dan 'required' berdasarkan pilihan
        if (type === 'existing') {
            formExisting.style.display = 'block';
            formManual.style.display = 'none';
            if (produkSelect) produkSelect.required = true;
            if (jumlahInput) jumlahInput.required = true;
            updateMaxQuantity();
        } else if (type === 'manual') {
            formExisting.style.display = 'none';
            formManual.style.display = 'block';
            if (namaManual) namaManual.required = true;
            if (kategoriManual) kategoriManual.required = true;
            if (hargaManual) hargaManual.required = true;
            if (deskripsiManual) deskripsiManual.required = true;
            if (fotoManual) fotoManual.required = true;
        } else {
            formExisting.style.display = 'none';
            formManual.style.display = 'none';
        }
    }

    function updateMaxQuantity() {
        var select = document.getElementById('produk_ditawarkan_id');
        if (!select) return;

        var inputJumlah = document.getElementById('jumlah_ditawarkan');
        var stokInfo = document.getElementById('stok-info');
        var selectedOption = select.options[select.selectedIndex];
        var stok = selectedOption.getAttribute('data-stok');

        if (stok) {
            inputJumlah.max = stok;
            inputJumlah.disabled = false;
            stokInfo.textContent = '(Stok tersedia: ' + stok + ')';
            if (parseInt(inputJumlah.value) > parseInt(stok)) {
                inputJumlah.value = stok;
            }
            if (parseInt(inputJumlah.value) < 1) {
                inputJumlah.value = 1;
            }
        } else {
            inputJumlah.max = 1;
            inputJumlah.disabled = true;
            stokInfo.textContent = '(Pilih produk untuk mengisi jumlah)';
            inputJumlah.value = 1;
        }
    }

    // Panggil fungsi saat halaman dimuat untuk menangani 'old()'
    document.addEventListener('DOMContentLoaded', function() {
        toggleOfferType();
        // Panggil updateMaxQuantity hanya jika tipe = existing
        if (document.getElementById('tipe_tawaran').value === 'existing') {
            updateMaxQuantity();
        }
    });
</script>
@endsection
