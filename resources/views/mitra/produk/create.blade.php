@extends('mitra.layouts.app')
@section('title', 'Tambah Produk Baru')
@section('content')
    <h1>Tambah Produk Baru</h1>

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

    <form action="{{ route('mitra.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-top: 10px;">
            <label for="nama_produk">Nama Produk</label><br>
            <input type="text" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="kategori_id">Kategori Produk</label><br>
            <select id="kategori_id" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id') == $kategori->kategori_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label for="tipe_penawaran">Tipe Penawaran (Fitur 1 & 2)</label><br>
            <select id="tipe_penawaran" name="tipe_penawaran" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="Jual-Cepat" {{ old('tipe_penawaran') == 'Jual-Cepat' ? 'selected' : '' }}>Jual Cepat (Surplus)</option>
                <option value="Donasi" {{ old('tipe_penawaran') == 'Donasi' ? 'selected' : '' }}>Donasi (Gratis)</option>
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label for="harga_normal">Harga Normal (Isi 0 jika Donasi)</label><br>
            <input type="number" id="harga_normal" name="harga_normal" value="{{ old('harga_normal', 0) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="harga_diskon">Harga Jual / Diskon (Isi 0 jika Donasi)</label><br>
            <input type="number" id="harga_diskon" name="harga_diskon" value="{{ old('harga_diskon', 0) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="stok_awal">Stok Awal</label><br>
            <input type="number" id="stok_awal" name="stok_awal" value="{{ old('stok_awal', 1) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="waktu_kadaluarsa">Tanggal Kadaluarsa</label><br>
            <input type="datetime-local" id="waktu_kadaluarsa" name="waktu_kadaluarsa" value="{{ old('waktu_kadaluarsa') }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="waktu_ambil_mulai">Waktu Ambil Mulai</label><br>
            <input type="datetime-local" id="waktu_ambil_mulai" name="waktu_ambil_mulai" value="{{ old('waktu_ambil_mulai') }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="waktu_ambil_selesai">Waktu Ambil Selesai</label><br>
            <input type="datetime-local" id="waktu_ambil_selesai" name="waktu_ambil_selesai" value="{{ old('waktu_ambil_selesai') }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="4" style="width: 300px;">{{ old('deskripsi') }}</textarea>
        </div>

        <div style="margin-top: 10px;">
            <label for="foto_produk">Foto Produk</label><br>
            <input type="file" id="foto_produk" name="foto_produk">
        </div>
        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px;">Simpan Produk</button>
        </div>
    </form>
@endsection
