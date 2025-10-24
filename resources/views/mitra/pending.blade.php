@extends('mitra.layouts.app')

@section('title', 'Menunggu Verifikasi')

@section('content')
    <h1>Terima Kasih Telah Mendaftar, {{ $mitra->nama_mitra }}!</h1>

    <div style="padding: 20px; background: #fffbe6; border: 1px solid #ffe58f;">
        <h3>Akun Anda Sedang Ditinjau</h3>
        <p>
            Saat ini, akun Anda sedang dalam proses verifikasi oleh tim Admin kami.
            Status akun Anda saat ini: <strong>PENDING</strong>.
        </p>
        <p>
            Tim kami akan memeriksa data yang Anda kirimkan. Anda akan dapat mengakses dashboard dan
            mulai menjual produk setelah akun Anda disetujui.
        </p>
        <p>
            Silakan cek kembali nanti atau hubungi Admin jika proses verifikasi memakan waktu lebih dari 2x24 jam.
        </p>
    </div>
@endsection
