@extends('admin.layouts.app')

@section('title', 'Detail Mitra: ' . $mitra->nama_mitra)

@section('content')
    <h1>Detail Mitra: {{ $mitra->nama_mitra }}</h1>

    <a href="{{ route('admin.mitra.index') }}">&larr; Kembali ke Daftar</a>

    <table border="1" style="width: 100%; margin-top: 20px;">
        <tr>
            <th style="width: 200px;">ID Mitra</th>
            <td>{{ $mitra->mitra_id }}</td>
        </tr>
        <tr>
            <th>Nama Mitra</th>
            <td>{{ $mitra->nama_mitra }}</td>
        </tr>
        <tr>
            <th>Email Bisnis</th>
            <td>{{ $mitra->email_bisnis }}</td>
        </tr>
        <tr>
            <th>Nomor Telepon</th>
            <td>{{ $mitra->nomor_telepon }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $mitra->alamat }}</td>
        </tr>
        <tr>
            <th>Deskripsi</th>
            <td>{{ $mitra->deskripsi ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status Verifikasi</th>
            <td>
                @if($mitra->status_verifikasi == 'Verified')
                    <span style="color: green; font-weight: bold;">Verified</span>
                @elseif($mitra->status_verifikasi == 'Pending')
                    <span style="color: orange; font-weight: bold;">Pending</span>
                @else
                    <span style="color: red; font-weight: bold;">Rejected</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Tanggal Daftar</th>
            <td>{{ $mitra->created_at->format('d M Y, H:i') }}</td>
        </tr>
    </table>

    <hr>

    @if(auth()->guard('admin')->user()->role === 'SuperAdmin')
        <a href="{{ route('admin.mitra.edit', $mitra->mitra_id) }}" style="background: blue; color: white; padding: 10px;">
            Edit Mitra Ini
        </a>
    @endif

@endsection
