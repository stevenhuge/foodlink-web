@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-2 text-primary">Selamat Datang di Dashboard Admin</h1>
                    <p class="text-muted">Ini adalah halaman utama panel admin.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="h4 mb-3">Statistik Platform</h3>
        </div>

        <!-- Total Users Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total User Terdaftar</h5>
                            <h2 class="mb-0">{{ $jumlahUser }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Mitra Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Mitra Terdaftar</h5>
                            <h2 class="mb-0">{{ $jumlahMitra }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-handshake fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Mitra Card -->
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-dark shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Mitra Menunggu Verifikasi</h5>
                            <h2 class="mb-0">{{ $mitraPending }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.mitra.index') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Lihat Verifikasi Mitra
                </a>

                @can('is-superadmin')
                <a href="{{ route('admin.admins.index') }}" class="btn btn-info btn-lg text-white">
                    <i class="fas fa-users-cog me-2"></i>
                    Lihat Manajemen Admin
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
</style>
@endpush
