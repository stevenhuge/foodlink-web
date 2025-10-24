@extends('admin.layouts.app')

@section('title', 'Edit Mitra: ' . $mitra->nama_mitra)

@section('content')
    <h1>Edit Mitra: {{ $mitra->nama_mitra }}</h1>

    <form action="{{ route('admin.mitra.update', $mitra->mitra_id) }}" method="POST">
        @csrf
        @method('PUT') <div>
            <label for="nama_mitra">Nama Mitra</label><br>
            <input type="text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="email_bisnis">Email Bisnis</label><br>
            <input type="email" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis', $mitra->email_bisnis) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="nomor_telepon">Nomor Telepon</label><br>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $mitra->nomor_telepon) }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="alamat">Alamat</label><br>
            <textarea id="alamat" name="alamat" rows="3" style="width: 300px;" required>{{ old('alamat', $mitra->alamat) }}</textarea>
        </div>

        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 300px;">{{ old('deskripsi', $mitra->deskripsi) }}</textarea>
        </div>

        <hr>

        <h3>Reset Password (Hanya SuperAdmin)</h3>
        <p>Biarkan kosong jika Anda tidak ingin mengubah password Mitra.</p>

        <div style="margin-top: 10px;">
            <label for="password_baru">Password Baru</label><br>
            <input type="password" id="password_baru" name="password_baru">
        </div>

        <div style="margin-top: 10px;">
            <label for="password_baru_confirmation">Konfirmasi Password Baru</label><br>
            <input type="password" id="password_baru_confirmation" name="password_baru_confirmation">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px;">Simpan Perubahan</button>
        </div>
    </form>
@endsection
