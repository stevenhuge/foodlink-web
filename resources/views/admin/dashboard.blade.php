@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            {{-- Mengubah inline style menjadi warna yang diminta --}}
            <h1 class="h3 mb-0 text-dark fw-bold">Dashboard Overview</h1>
            <p class="text-muted mb-0 small mt-1">Ringkasan data dan aktivitas platform Foodlink hari ini.</p>
        </div>
        <div class="d-none d-sm-inline-block mt-3 mt-sm-0">
            <span class="badge bg-white text-secondary border shadow-sm px-4 py-2 rounded-pill fw-medium text-dark" style="font-size: 0.85rem;">
                <i class="bi bi-calendar3 me-2 text-primary"></i> {{ date('d F Y') }}
            </span>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="letter-spacing: 0.5px;">
                                Total User (Pembeli)</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($jumlahUser) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary bg-opacity-10">
                                <i class="bi bi-people-fill text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1" style="letter-spacing: 0.5px;">
                                Total Mitra (Penjual)</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">{{ number_format($jumlahMitra) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info bg-opacity-10">
                                <i class="bi bi-shop text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat border-0 shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="letter-spacing: 0.5px;">
                                Menunggu Verifikasi</div>
                            <div class="h4 mb-0 font-weight-bold text-dark">
                                {{ $mitraPending }}
                                <small class="text-muted text-xs ms-1 fw-normal">Permintaan</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning bg-opacity-10">
                                <i class="bi bi-person-lines-fill text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header py-3 bg-white border-bottom-0 pt-4 px-4">
                    <h6 class="m-0 font-weight-bold text-dark fs-5"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Akses Cepat</h6>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        {{-- Tombol Verifikasi --}}
                        <div class="col-md-6">
                            <a href="{{ route('admin.mitra.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded-4 text-decoration-none">
                                <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-4 me-3 p-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">Verifikasi Mitra</h6>
                                    <small class="text-muted">Cek mitra baru mendaftar</small>
                                </div>
                            </a>
                        </div>

                        {{-- Tombol User --}}
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded-4 text-decoration-none">
                                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-4 me-3 p-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">Data User</h6>
                                    <small class="text-muted">Kelola akun pengguna</small>
                                </div>
                            </a>
                        </div>

                        {{-- Tombol Kategori --}}
                        <div class="col-md-6">
                            <a href="{{ route('admin.kategori-usaha.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded-4 text-decoration-none">
                                <div class="icon-box bg-info bg-opacity-10 text-info rounded-4 me-3 p-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <i class="bi bi-tags fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">Kategori Usaha</h6>
                                    <small class="text-muted">Atur kategori mitra</small>
                                </div>
                            </a>
                        </div>

                        {{-- Tombol Superadmin Only --}}
                        @can('is-superadmin')
                        <div class="col-md-6">
                            <a href="{{ route('admin.admins.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded-4 text-decoration-none">
                                <div class="icon-box bg-secondary bg-opacity-10 text-secondary rounded-4 me-3 p-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <i class="bi bi-gear fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">Admin System</h6>
                                    <small class="text-muted">Kelola akses admin</small>
                                </div>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 mb-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, var(--foodlink-primary) 0%, var(--foodlink-primary-hover) 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="m-0 font-weight-bold text-white"><i class="bi bi-info-circle me-2 opacity-75"></i>Informasi Akun</h5>
                    </div>
                    <div class="text-center mb-4">
                        <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-badge text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-1 text-center">Halo, {{ Auth::user()->nama_lengkap }}!</h4>
                    <p class="text-center text-white-50 small mb-4">Sistem Administrator</p>
                    
                    <div class="bg-white bg-opacity-10 rounded-3 p-3 text-sm">
                        <p class="mb-0 small text-white" style="line-height: 1.6;">
                            Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.
                            Pastikan untuk selalu memverifikasi data mitra dengan teliti demi keamanan platform Foodlink.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* Card Stats Styling - Bersih & Rapi */
    .card-stat {
        transition: transform 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
    }

    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
    }

    /* Text Colors overrides for custom CSS */
    .text-gray-800 { color: #1e293b !important; }

    /* Icon Circle Styling */
    .icon-circle {
        height: 3.5rem;
        width: 3.5rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Quick Action Button Styling */
    .btn-quick-action {
        transition: all 0.25s;
        background-color: #ffffff;
        border-color: #f1f5f9 !important;
    }
    .btn-quick-action:hover {
        background-color: #ffffff;
        border-color: #e2e8f0 !important;
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
    }
    
    .btn-quick-action .icon-box {
        transition: transform 0.25s ease;
    }
    
    .btn-quick-action:hover .icon-box {
        transform: scale(1.05);
    }
</style>
@endpush