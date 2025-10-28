{{-- resources/views/admin/kategori-usaha/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Kategori Usaha')

@section('content')
    <h1>Edit Kategori Usaha: {{ $kategoriUsaha->nama_kategori }}</h1>

    {{-- Tampilkan error validasi jika ada --}}
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

    <form action="{{ route('admin.kategori-usaha.update', $kategoriUsaha->kategori_usaha_id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Gunakan method PUT untuk update --}}
        <div>
            <label for="nama_kategori">Nama Kategori</label><br>
            {{-- Isi value dengan data kategori yang sedang diedit --}}
            <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategoriUsaha->nama_kategori) }}" required autofocus style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
            <a href="{{ route('admin.kategori-usaha.index') }}" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
@endsection
