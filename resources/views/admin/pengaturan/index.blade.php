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
                <div class="col-md-6 border-end">
                    <h5 class="mb-3">Nilai Tarif</h5>

                    @foreach($settings as $setting)
                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ $setting->label }}</label>
                        <div class="input-group">
                            <input type="number" name="settings[{{ $setting->key }}]"
                                class="form-control" value="{{ $setting->value }}" required>

                            @if(str_contains($setting->key, 'persen'))
                                <span class="input-group-text bg-warning text-dark fw-bold">%</span>
                            @else
                                <span class="input-group-text bg-success text-white fw-bold">IDR</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Notifikasi Perubahan</h5>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i>
                        Setiap perubahan nilai pajak, <strong>WAJIB</strong> menyertakan keterangan. Pesan ini akan masuk ke <strong>Inbox Mitra</strong>.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan / Alasan Perubahan</label>
                        <textarea name="keterangan_perubahan" class="form-control" rows="5"
                                  placeholder="Contoh: Mulai tanggal 1 Januari, PPN naik menjadi 12% sesuai regulasi pemerintah..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i> Simpan & Kirim Notifikasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
