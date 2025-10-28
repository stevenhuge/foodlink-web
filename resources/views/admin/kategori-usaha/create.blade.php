{{-- resources/views/admin/kategori-usaha/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Usaha Baru')

@section('content')
    <h1>Tambah Kategori Usaha Baru</h1>

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

    <form action="{{ route('admin.kategori-usaha.store') }}" method="POST">
        @csrf
        <div>
            <label for="nama_kategori">Nama Kategori</label><br>
            <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}" required autofocus style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">Simpan Kategori</button>
            <a href="{{ route('admin.kategori-usaha.index') }}" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
@endsection
