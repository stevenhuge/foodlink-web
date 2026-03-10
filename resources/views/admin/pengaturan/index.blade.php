@extends('admin.layouts.app')

@section('title', 'Pengaturan Pajak & Biaya')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pengaturan Sistem</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Left Panel: Biaya & Pajak Pembeli --}}
                <div class="col-md-6 border-end pe-4">
                    <h5 class="mb-4 text-primary fw-bold border-bottom pb-2">Dibebankan ke Pembeli (Aplikasi)</h5>
                    <p class="text-muted small mb-4">Pengaturan ini akan dipotong langsung dari total bayar User di aplikasi Android.</p>

                    @foreach($settings as $setting)
                        @if(in_array($setting->key, ['biaya_ppn_persen', 'biaya_layanan_user']))
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">{{ $setting->label }}</label>
                            <div class="input-group input-group-lg shadow-sm">
                                <input type="number" name="settings[{{ $setting->key }}]"
                                    class="form-control" value="{{ $setting->value }}" required>

                                @if(str_contains($setting->key, 'persen'))
                                    <span class="input-group-text bg-warning text-dark fw-bold">%</span>
                                @else
                                    <span class="input-group-text bg-success text-white fw-bold">IDR</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- Right Panel: Potongan Mitra --}}
                <div class="col-md-6 ps-4">
                    <h5 class="mb-4 text-info fw-bold border-bottom pb-2">Potongan Komisi Penjual (Mitra)</h5>
                    <p class="text-muted small mb-4">Persentase MDR (Merchant Discount Rate) yang dipotong dari total penjualan kotor Mitra.</p>

                    @foreach($settings as $setting)
                        @if(in_array($setting->key, ['biaya_mitra_persen']))
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">{{ $setting->label }}</label>
                            <div class="input-group input-group-lg shadow-sm">
                                <input type="number" name="settings[{{ $setting->key }}]"
                                    class="form-control" value="{{ $setting->value }}" required>
                                <span class="input-group-text bg-info text-white fw-bold">%</span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <hr class="my-4">

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h5 class="mb-3 text-dark fw-bold"><i class="bi bi-bell-fill text-warning me-2"></i>Notifikasi Perubahan Kebijakan</h5>
                    <div class="alert alert-primary text-dark small border-0 shadow-sm">
                        <i class="bi bi-info-circle-fill me-1 text-primary"></i>
                        Setiap perubahan nilai pajak, <strong>WAJIB</strong> menyertakan keterangan. Pesan ini akan di-broadcast ke <strong>Inbox seluruh Mitra</strong> untuk asas transparansi.
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Keterangan / Alasan Perubahan (Broadcast Message)</label>
                        <textarea name="keterangan_perubahan" class="form-control bg-light" rows="4"
                                  placeholder="Contoh: Mulai tanggal 1 Januari, PPN naik menjadi 12% sesuai regulasi pemerintah DJP terbaru..." required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Simpan Perubahan & Broadcast
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
