@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Selamat Datang di Dashboard Admin</h1>
    <p>Ini adalah halaman utama panel admin.</p>

    <h3>Statistik Platform</h3>
    <ul>
        <li>Total User Terdaftar: <strong>{{ $jumlahUser }}</strong></li>
        <li>Total Mitra Terdaftar: <strong>{{ $jumlahMitra }}</strong></li>
        <li>Mitra Menunggu Verifikasi: <strong>{{ $mitraPending }}</strong></li>
    </ul>

    <p style="margin-top: 50px;">
        <a href="{{ route('admin.mitra.index') }}" style="padding: 10px; background: orange;">Lihat Verifikasi Mitra</a>

        @can('is-superadmin')
             <a href="{{ route('admin.admins.index') }}" style="padding: 10px; background: cyan;">Lihat Manajemen Admin</a>
        @endcan
    </p>
@endsection
