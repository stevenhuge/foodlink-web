@extends('mitra.layouts.app')

@section('title', 'Dashboard Mitra')

@section('content')
    <div style="padding: 15px; background: #d4edda; color: #155724; margin-bottom: 20px;">
        Akun Anda telah <strong>DIVERIFIKASI</strong>. Selamat datang!
    </div>

    <h1>Dashboard Mitra: {{ $mitra->nama_mitra }}</h1>
    <p>Di halaman ini, Anda dapat mengelola produk surplus Anda.</p>

    <div style="margin-top: 20px;">

        <a href="{{ route('mitra.produk.create') }}" style="background: #007bff; color: white; padding: 15px; text-decoration: none; font-size: 18px;">
            + Tambah Produk (Jual Cepat / Donasi)
        </a>
        </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('mitra.produk.index') }}" style="background: #6c757d; color: white; padding: 15px; text-decoration: none; font-size: 18px;">
            Lihat & Kelola Produk Saya
        </a>
    </div>

<div style="margin-top: 20px;">
    <a href="{{ route('mitra.barter.index') }}" style="background: #17a2b8; color: white; padding: 15px; text-decoration: none; font-size: 18px;">
        Lihat Marketplace Barter
    </a>
</div>

    <div style="margin-top: 20px;">
        <a href="{{ route('mitra.barter.inbox') }}" style="background: #ffc107; color: black; padding: 15px; text-decoration: none; font-size: 18px;">
            Inbox Penawaran Barter
        </a>
    </div>

    <h3 style="margin-top: 30px;">Riwayat Transaksi</h3>
    <p><em>(Riwayat produk yang diambil user akan muncul di sini...)</em></p>
@endsection
