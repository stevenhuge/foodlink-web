@extends('admin.layouts.app')

@section('title', 'Pengaturan Pajak & Biaya')

@section('content')
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1 text-gray-800 fw-bold">Pengaturan Sistem</h2>
            <p class="text-muted mb-0">Kelola besaran pajak, biaya layanan, dan potongan komisi.</p>
        </div>
    </div>

    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4 mb-4">
            <!-- Section 1: Dibebankan ke Pembeli -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Beban Pembeli</h5>
                                <small class="text-muted">Dipotong dari total bayar User (Aplikasi)</small>
                            </div>
                        </div>
                        <hr class="mb-0 mt-3 border-light">
                    </div>
                    <div class="card-body p-4">
                        @foreach($settings as $setting)
                            @if(in_array($setting->key, ['biaya_ppn_persen', 'biaya_layanan_user']))
                                <div class="mb-4 last:mb-0">
                                    <label class="form-label fw-semibold text-secondary mb-2">{{ $setting->label }}</label>
                                    <div class="input-group input-group-lg shadow-none">
                                        <input type="number" name="settings[{{ $setting->key }}]" class="form-control bg-light border-0" value="{{ $setting->value }}" required>
                                        @if(str_contains($setting->key, 'persen'))
                                            <span class="input-group-text bg-white border-0 text-muted fw-bold">%</span>
                                        @else
                                            <span class="input-group-text bg-white border-0 text-muted fw-bold">IDR</span>
                                        @endif
                                    </div>
                                    <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Besaran {{ strtolower($setting->label) }} saat ini.</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Section 2: Dibebankan ke Mitra -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info bg-opacity-10 text-info rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="bi bi-shop fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Potongan Mitra</h5>
                                <small class="text-muted">Dipotong dari total penjualan Mitra (MDR)</small>
                            </div>
                        </div>
                        <hr class="mb-0 mt-3 border-light">
                    </div>
                    <div class="card-body p-4">
                        @foreach($settings as $setting)
                            @if(in_array($setting->key, ['biaya_mitra_persen']))
                                <div class="mb-4 last:mb-0">
                                    <label class="form-label fw-semibold text-secondary mb-2">{{ $setting->label }}</label>
                                    <div class="input-group input-group-lg shadow-none">
                                        <input type="number" name="settings[{{ $setting->key }}]" class="form-control bg-light border-0" value="{{ $setting->value }}" required>
                                        <span class="input-group-text bg-white border-0 text-muted fw-bold">%</span>
                                    </div>
                                    <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Persentase MDR untuk setiap transaksi.</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Notifikasi Perubahan -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center">
                            <div class="col-md-5 mb-4 mb-md-0">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-bell-fill text-warning me-2"></i> Notifikasi Perubahan
                                </h5>
                                <p class="text-muted mb-0 pe-md-4" style="font-size: 0.95rem;">
                                    Setiap perubahan pada besaran pajak atau komisi <strong>WAJIB</strong> menyertakan keterangan. Pesan ini akan dikirimkan sebagai <em>broadcast</em> ke seluruh <strong>Inbox Mitra</strong> demi menjaga transparansi.
                                </p>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark">Keterangan / Alasan Perubahan</label>
                                    <textarea name="keterangan_perubahan" class="form-control bg-light border-0 rounded-3 p-3" rows="3" placeholder="Contoh: Mulai tanggal 1 Januari, PPN naik menjadi 12% sesuai regulasi pemerintah..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-medium d-inline-flex align-items-center">
                                        <i class="bi bi-cloud-arrow-up-fill me-2 fs-5"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection
