@extends('mitra.layouts.app')
@section('title', 'Edit Produk: ' . $produk->nama_produk)
@section('content')
    <h1>Edit Produk: {{ $produk->nama_produk }}</h1>

    @if ($errors->any())
        <div style="color: red;">
            <strong>Oops! Ada kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mitra.produk.update', $produk->produk_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-top: 10px;">
            <label for="nama_produk">Nama Produk</label><br>
            <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="kategori_id">Kategori Produk</label><br>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->kategori_id }}" @if(old('kategori_id', $produk->kategori_id) == $kategori->kategori_id) selected @endif>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label for="tipe_penawaran">Tipe Penawaran</label><br>
            <select id="tipe_penawaran" name="tipe_penawaran" required>
                <option value="Jual-Cepat" @if(old('tipe_penawaran', $produk->tipe_penawaran) == 'Jual-Cepat') selected @endif>Jual Cepat (Surplus)</option>
                <option value="Donasi" @if(old('tipe_penawaran', $produk->tipe_penawaran) == 'Donasi') selected @endif>Donasi (Gratis)</option>
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label for="harga_normal">Harga Normal</label><br>
            <input type="number" id="harga_normal" name="harga_normal" value="{{ old('harga_normal', $produk->harga_normal) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="harga_diskon">Harga Jual / Diskon</label><br>
            <input type="number" id="harga_diskon" name="harga_diskon" value="{{ old('harga_diskon', $produk->harga_diskon) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="stok_awal">Stok Awal</label><br>
            <input type="number" id="stok_awal" name="stok_awal" value="{{ old('stok_awal', $produk->stok_awal) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="stok_tersisa">Stok Tersisa</label><br>
            <input type="number" id="stok_tersisa" name="stok_tersisa" value="{{ old('stok_tersisa', $produk->stok_tersisa) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="status_produk">Status Produk</label><br>
            <select id="status_produk" name="status_produk" required>
                <option value="Tersedia" @if(old('status_produk', $produk->status_produk) == 'Tersedia') selected @endif>Tersedia</option>
                <option value="Habis" @if(old('status_produk', $produk->status_produk) == 'Habis') selected @endif>Habis</option>
                <option value="Ditarik" @if(old('status_produk', $produk->status_produk) == 'Ditarik') selected @endif>Ditarik (Sembunyikan)</option>
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label for="waktu_kadaluarsa">Tanggal Kadaluarsa</label><br>
            <input type="datetime-local" id="waktu_kadaluarsa" name="waktu_kadaluarsa" value="{{ old('waktu_kadaluarsa', $produk->waktu_kadaluarsa->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="waktu_ambil_mulai">Waktu Ambil Mulai</label><br>
            <input type="datetime-local" id="waktu_ambil_mulai" name="waktu_ambil_mulai" value="{{ old('waktu_ambil_mulai', $produk->waktu_ambil_mulai->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="waktu_ambil_selesai">Waktu Ambil Selesai</label><br>
            <input type="datetime-local" id="waktu_ambil_selesai" name="waktu_ambil_selesai" value="{{ old('waktu_ambil_selesai', $produk->waktu_ambil_selesai->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 300px;">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
        </div>

        <div style="margin-top: 10px;">
            <label for="foto_produk">Foto Produk (Kosongkan jika tidak ingin ganti)</label><br>

            @if($produk->foto_produk)
                <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 10px; display: block;">
            @else
                <p>(Belum ada foto)</p>
            @endif

            <input type="file" id="foto_produk" name="foto_produk">
        </div>
        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px;">Simpan Perubahan</button>
        </div>
    </form>
@endsection
