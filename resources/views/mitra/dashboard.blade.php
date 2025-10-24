@extends('mitra.layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
    <div style="padding: 15px; background: #d4edda; color: #155724; margin-bottom: 20px;">
        Akun Anda telah <strong>DIVERIFIKASI</strong>. Selamat datang!
    </div>

    <h1>Dashboard Mitra: {{ $mitra->nama_mitra }}</h1>
    <p>Di halaman ini, Anda dapat mengelola produk surplus Anda.</p>

    <div style="margin-top: 20px;">
        <a href="#" style="background: #007bff; color: white; padding: 15px; text-decoration: none; font-size: 18px;">
            + Tambah Produk (Jual Cepat / Donasi)
        </a>
    </div>

    <h3 style="margin-top: 30px;">Produk Anda Saat Ini</h3>
    <p><em>(Daftar produk Anda akan muncul di sini...)</em></p>

    <h3 style="margin-top: 30px;">Riwayat Transaksi</h3>
    <p><em>(Riwayat produk yang diambil user akan muncul di sini...)</em></p>
@endsection
