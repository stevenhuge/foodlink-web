@extends('mitra.layouts.app')
@section('title', 'Ajukan Barter')
@section('content')
    <h1>Ajukan Penawaran Barter</h1>
    <a href="{{ route('mitra.barter.index') }}">&larr; Kembali ke Marketplace</a>

    {{-- Produk yang Anda Minta --}}
    <div style="border: 1px solid #ccc; padding: 15px; margin-top: 20px; background: #f8f9fa;"> {{-- Beri sedikit latar belakang --}}
        <h3>Produk yang Anda Minta:</h3>
        {{-- === KODE UNTUK MENAMPILKAN PRODUK DIMINTA === --}}
        <div style="display: flex; align-items: center;">
            @if($produk->foto_produk)
                <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px; border-radius: 4px;">
            @else
                <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center; margin-right: 15px; border-radius: 4px; font-size: 0.8em; color: #666;">No Img</div>
            @endif
            <div>
                <strong>{{ $produk->nama_produk }}</strong><br>
                Oleh: {{ $produk->mitra->nama_mitra ?? 'N/A' }}<br>
                Harga Perkiraan: Rp {{ number_format($produk->harga_diskon) }} <br>
                Stok Tersedia: {{ $produk->stok_tersisa }}
            </div>
        </div>
        {{-- ============================================= --}}
    </div>

    {{-- Form Penawaran BARU dengan Opsi --}}
    <form action="{{ route('mitra.barter.store', $produk->produk_id) }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf

        {{-- Tampilkan Error --}}
        @if ($errors->any())
            <div class="error-msg">
                 <strong>Oops! Ada kesalahan:</strong>
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div>
        @endif
        @if (session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        {{-- Pilihan Opsi 1 vs Opsi 2 --}}
        <div style="margin-top: 10px;">
            <label for="tipe_tawaran"><strong>Pilih Opsi Tawaran Anda:</strong></label><br>
            <select id="tipe_tawaran" name="tipe_tawaran" required onchange="toggleOfferType()">
                <option value="">-- Pilih Cara Menawar --</option>
                <option value="existing" {{ old('tipe_tawaran') == 'existing' ? 'selected' : '' }}>1. Tawarkan dari Produk Saya yang Ada</option>
                <option value="manual" {{ old('tipe_tawaran') == 'manual' ? 'selected' : '' }}>2. Tawarkan Barang Lain (Input Manual)</option>
            </select>
        </div>

        {{-- Form untuk OPSI 1 (Existing) --}}
        <div id="form-existing" style="display:none; border: 1px solid blue; padding: 15px; margin-top: 10px;">
            {{-- Dropdown Produk --}}
            <div style="margin-bottom: 10px;">
                <label for="produk_ditawarkan_id"><strong>Pilih Produk Anda yang Tersedia:</strong></label><br>
                <select id="produk_ditawarkan_id" name="produk_ditawarkan_id" style="width: 100%;" onchange="updateMaxQuantity()">
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
                <label for="jumlah_ditawarkan"><strong>Jumlah yang Ditawarkan:</strong></label><br>
                <input type="number" id="jumlah_ditawarkan" name="jumlah_ditawarkan" value="{{ old('jumlah_ditawarkan', 1) }}" min="1" max="1">
                <span id="stok-info" style="font-size: 0.9em; color: grey;"></span>
            </div>
        </div>

        {{-- Form untuk OPSI 2 (Manual) --}}
        <div id="form-manual" style="display:none; border: 1px solid green; padding: 15px; margin-top: 10px;">
             <p>Anda akan menawarkan barang baru. Silakan isi datanya seperti menambah produk.</p>
             {{-- Nama Barang --}}
             <div style="margin-top: 10px;">
                 <label for="nama_barang_manual">Nama Barang</label><br>
                 <input type="text" id="nama_barang_manual" name="nama_barang_manual" value="{{ old('nama_barang_manual') }}" style="width: 100%;">
             </div>
             {{-- Kategori Barang --}}
             <div style="margin-top: 10px;">
                 <label for="kategori_id_manual">Kategori Barang</label><br>
                 <select id="kategori_id_manual" name="kategori_id" style="width: 100%;"> {{-- Name tetap 'kategori_id' --}}
                     <option value="">-- Pilih Kategori --</option>
                     @foreach($kategoris as $kategori)
                         <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_id') == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                             {{ $kategori->nama_kategori }}
                         </option>
                     @endforeach
                 </select>
             </div>
              {{-- Harga Perkiraan --}}
             <div style="margin-top: 10px;">
                 <label for="harga_perkiraan">Perkiraan Harga (Rp)</label><br>
                 <input type="number" id="harga_perkiraan" name="harga_perkiraan" value="{{ old('harga_perkiraan', 0) }}" min="0">
             </div>
             {{-- Deskripsi --}}
             <div style="margin-top: 10px;">
                 <label for="deskripsi_barang_manual">Deskripsi Barang</label><br>
                 <textarea id="deskripsi_barang_manual" name="deskripsi_barang_manual" rows="4" style="width: 100%;">{{ old('deskripsi_barang_manual') }}</textarea>
             </div>
             {{-- Foto Barang --}}
             <div style="margin-top: 10px;">
                 <label for="foto_barang_manual">Foto Barang (Wajib, Max 1MB)</label><br>
                 <input type="file" id="foto_barang_manual" name="foto_barang_manual">
             </div>
             {{-- Bukti Struk --}}
             <div style="margin-top: 10px;">
                 <label for="bukti_struk">Bukti Struk (Opsional, Max 1MB)</label><br>
                 <input type="file" id="bukti_struk" name="bukti_struk">
             </div>
        </div>

        {{-- Tombol Kirim --}}
        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px;">Kirim Penawaran</button>
        </div>
    </form>

    {{-- JavaScript (Tidak berubah) --}}
    <script>
        function toggleOfferType() {
            var type = document.getElementById('tipe_tawaran').value;
            var formExisting = document.getElementById('form-existing');
            var formManual = document.getElementById('form-manual');
            var produkSelect = document.getElementById('produk_ditawarkan_id');
            var jumlahInput = document.getElementById('jumlah_ditawarkan');
            var namaManual = document.getElementById('nama_barang_manual');
            var kategoriManual = document.getElementById('kategori_id_manual'); // Tambahkan ini
            var hargaManual = document.getElementById('harga_perkiraan'); // Tambahkan ini
            var deskripsiManual = document.getElementById('deskripsi_barang_manual'); // Tambahkan ini
            var fotoManual = document.getElementById('foto_barang_manual');

            // Reset required status agar validasi backend yang utama
            produkSelect.required = false;
            jumlahInput.required = false;
            namaManual.required = false;
            kategoriManual.required = false;
            hargaManual.required = false;
            deskripsiManual.required = false;
            fotoManual.required = false;

            if (type === 'existing') {
                formExisting.style.display = 'block';
                formManual.style.display = 'none';
                produkSelect.required = true; // Bantu user di frontend
                jumlahInput.required = true;  // Bantu user di frontend
                updateMaxQuantity();
            } else if (type === 'manual') {
                formExisting.style.display = 'none';
                formManual.style.display = 'block';
                namaManual.required = true; // Bantu user di frontend
                kategoriManual.required = true; // Bantu user di frontend
                hargaManual.required = true; // Bantu user di frontend
                deskripsiManual.required = true; // Bantu user di frontend
                fotoManual.required = true; // Bantu user di frontend
            } else {
                formExisting.style.display = 'none';
                formManual.style.display = 'none';
            }
        }

        function updateMaxQuantity() {
            var select = document.getElementById('produk_ditawarkan_id');
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
                 if (parseInt(inputJumlah.value) < 1) { // Pastikan min 1
                    inputJumlah.value = 1;
                }
            } else {
                inputJumlah.max = 1;
                inputJumlah.disabled = true;
                stokInfo.textContent = '(Pilih produk untuk mengisi jumlah)';
                inputJumlah.value = 1;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
             toggleOfferType();
             // Panggil updateMaxQuantity hanya jika tipe = existing
             if (document.getElementById('tipe_tawaran').value === 'existing') {
                updateMaxQuantity();
             }
        });
    </script>
@endsection
