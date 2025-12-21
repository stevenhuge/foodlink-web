@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            {{-- Mengubah inline style menjadi warna yang diminta --}}
            <h1 class="h3 mb-0 text-gray-800 fw-bold" style="color: #4db43f;">Dashboard Overview</h1>
            <p class="text-muted mb-0 small">Ringkasan data dan aktivitas platform Foodlink hari ini.</p>
        </div>
        <div class="d-none d-sm-inline-block">
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                <i class="far fa-calendar-alt me-2"></i> {{ date(format: 'd F Y') }}
            </span>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat border-start-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total User (Pembeli)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($jumlahUser) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary-light">
                                <i class="fas fa-users text-primary fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat border-start-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Mitra (Penjual)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($jumlahMitra) }}</div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info-light">
                                <i class="fas fa-store text-info fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat border-start-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Verifikasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $mitraPending }}
                                <small class="text-muted text-xs ms-1">Permintaan</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning-light">
                                <i class="fas fa-user-clock text-warning fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-rocket me-2"></i>Akses Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        {{-- Tombol Verifikasi --}}
                        <div class="col-md-6">
                            <a href="{{ route('admin.mitra.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                <div class="icon-box bg-warning-light text-warning rounded-circle me-3 p-3">
                                    <i class="fas fa-clipboard-check fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">Verifikasi Mitra</h6>
                                    <small class="text-muted">Cek mitra baru mendaftar</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                        </div>

                        {{-- Tombol User --}}
                        <div class="col-md-6">
                            <a href="{{ route('admin.users.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                <div class="icon-box bg-primary-light text-primary rounded-circle me-3 p-3">
                                    <i class="fas fa-users-cog fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">Data User</h6>
                                    <small class="text-muted">Kelola akun pengguna</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                        </div>

                        {{-- Tombol Kategori --}}
                        <div class="col-md-6">
                            <a href="{{ route('admin.kategori-usaha.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                <div class="icon-box bg-info-light text-info rounded-circle me-3 p-3">
                                    <i class="fas fa-tags fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">Kategori Usaha</h6>
                                    <small class="text-muted">Atur kategori mitra</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                        </div>

                        {{-- Tombol Superadmin Only --}}
                        @can('is-superadmin')
                        <div class="col-md-6">
                            <a href="{{ route('admin.admins.index') }}" class="btn-quick-action d-flex align-items-center p-3 border rounded text-decoration-none">
                                <div class="icon-box bg-secondary-light text-secondary rounded-circle me-3 p-3">
                                    <i class="fas fa-shield-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">Admin System</h6>
                                    <small class="text-muted">Kelola akses admin</small>
                                </div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm mb-4">
                {{-- Class bg-primary akan di-override di CSS --}}
                <div class="card-header py-3 bg-primary text-white border-0">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i>Informasi</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 10rem;"
                             src="https://img.freepik.com/free-vector/business-team-brainstorming-discussing-startup-project_74855-6909.jpg"
                             alt="Admin Illustration">
                    </div>
                    <p>Halo, <strong>{{ Auth::user()->nama_lengkap }}</strong>!</p>
                    <p class="mb-0 small text-muted">
                        Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.
                        Pastikan untuk selalu memverifikasi data mitra dengan teliti demi keamanan platform Foodlink.
                    </p>
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
        transition: transform 0.2s ease-in-out;
        border: none;
    }

    .card-stat:hover {
        transform: translateY(-5px);
    }

    /* --- PERUBAHAN WARNA UTAMA MENJADI #4db43f --- */

    /* Border Left Color Indicators */
    .border-start-primary { border-left: 0.25rem solid #4db43f !important; }
    .border-start-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-start-warning { border-left: 0.25rem solid #f6c23e !important; }

    /* Text Colors */
    .text-primary { color: #4db43f !important; }
    .text-info { color: #36b9cc !important; }
    .text-warning { color: #f6c23e !important; }
    .text-gray-800 { color: #5a5c69 !important; }

    /* Background Colors (Override Bootstrap bg-primary) */
    .bg-primary { background-color: #4db43f !important; }

    /* Soft Background for Icons */
    /* Menggunakan RGBA dari #4db43f (77, 180, 63) */
    .bg-primary-light { background-color: rgba(77, 180, 63, 0.1); }
    .bg-info-light { background-color: rgba(54, 185, 204, 0.1); }
    .bg-warning-light { background-color: rgba(246, 194, 62, 0.1); }
    .bg-secondary-light { background-color: rgba(133, 135, 150, 0.1); }

    /* Icon Circle Styling */
    .icon-circle {
        height: 3rem;
        width: 3rem;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Quick Action Button Styling */
    .btn-quick-action {
        transition: all 0.2s;
        background-color: #fff;
    }
    .btn-quick-action:hover {
        background-color: #f8f9fa;
        border-color: #4db43f !important; /* Update Border Hover */
        transform: translateX(5px);
    }
</style>
@endpush