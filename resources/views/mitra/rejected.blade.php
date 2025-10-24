@extends('mitra.layouts.app')

@section('title', 'Pendaftaran Ditolak')

@section('content')
    <h1>Pendaftaran Anda Ditolak</h1>

    <div style="padding: 20px; background: #f8d7da; border: 1px solid #f5c6cb;">
        <h3>Mohon Maaf, {{ $mitra->nama_mitra }}.</h3>
        <p>
            Setelah peninjauan, kami memutuskan untuk tidak melanjutkan pendaftaran Anda saat ini.
            Status akun Anda: <strong>REJECTED</strong>.
        </p>
        <p>
            Alasan umum penolakan adalah data yang tidak lengkap atau tidak sesuai dengan kriteria kemitraan kami.
            Silakan hubungi Admin untuk informasi lebih lanjut.
        </p>
    </div>
@endsection
