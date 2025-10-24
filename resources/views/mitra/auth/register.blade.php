@extends('mitra.layouts.app')

@section('title', 'Registrasi Mitra')

@section('content')
    <h2>Bergabung Menjadi Mitra Foodlink</h2>
    <p>Daftarkan usaha Anda untuk membantu mengurangi food waste.</p>

    <form method="POST" action="{{ route('mitra.register') }}">
        @csrf
        <div>
            <label for="nama_mitra">Nama Usaha / Mitra</label><br>
            <input type.text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra') }}" required autofocus>
        </div>

        <div style="margin-top: 10px;">
            <label for="email_bisnis">Email Bisnis</label><br>
            <input type="email" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="nomor_telepon">Nomor Telepon (PIC)</label><br>
            <input type.text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="alamat">Alamat Lengkap Usaha</label><br>
            <textarea id="alamat" name="alamat" rows="3" style="width: 300px;" required>{{ old('alamat') }}</textarea>
        </div>

        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi Singkat Usaha (Opsional)</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 300px;">{{ old('deskripsi') }}</textarea>
        </div>

        <hr>

        <div style="margin-top: 10px;">
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
        </div>

        <div style="margin-top: 10px;">
            <label for="password_confirmation">Konfirmasi Password</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Daftar Sekarang</button>
        </div>
    </form>
@endsection
