@extends('mitra.layouts.app')
@section('title', 'Tambah Voucher')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-dark mb-0">Buat Voucher Baru</h3>
    <a href="{{ route('mitra.voucher.index') }}" class="btn btn-light border shadow-sm rounded-pill px-4">
        <i class="fas fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('mitra.voucher.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Voucher</label>
                    <input type="text" class="form-control @error('nama_voucher') is-invalid @enderror" name="nama_voucher" value="{{ old('nama_voucher') }}" placeholder="Contoh: Promo Spesial Lebaran" required>
                    @error('nama_voucher')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kode Voucher</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="text" class="form-control text-uppercase @error('kode_voucher') is-invalid @enderror" name="kode_voucher" value="{{ old('kode_voucher') }}" placeholder="Contoh: LEBARAN25" required>
                        @error('kode_voucher')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tipe Potongan</label>
                    <select class="form-select @error('tipe_potongan') is-invalid @enderror" name="tipe_potongan" id="tipe_potongan" required>
                        <option value="persentase" {{ old('tipe_potongan') == 'persentase' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="nominal" {{ old('tipe_potongan') == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                    @error('tipe_potongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nilai Potongan</label>
                    <div class="input-group">
                        <span class="input-group-text" id="simbol_potongan">%</span>
                        <input type="number" class="form-control @error('nilai_potongan') is-invalid @enderror" name="nilai_potongan" value="{{ old('nilai_potongan') }}" placeholder="0" min="0" required>
                        @error('nilai_potongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Alokasi Produk</label>
                    <select class="form-select @error('alokasi') is-invalid @enderror" name="alokasi" id="alokasi" required>
                        <option value="semua_menu" {{ old('alokasi') == 'semua_menu' ? 'selected' : '' }}>Seluruh Menu</option>
                        <option value="menu_tertentu" {{ old('alokasi') == 'menu_tertentu' ? 'selected' : '' }}>Menu Tertentu</option>
                    </select>
                    @error('alokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3" id="produk_container" style="display: none;">
                    <label class="form-label fw-bold">Pilih Produk</label>
                    <select class="form-select @error('produk_id') is-invalid @enderror" name="produk_id">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->produk_id }}" {{ old('produk_id') == $produk->produk_id ? 'selected' : '' }}>{{ $produk->nama_produk }} (Stok: {{ $produk->stok_tersisa }})</option>
                        @endforeach
                    </select>
                    @error('produk_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Minimal Belanja (Rp)</label>
                    <input type="number" class="form-control @error('minimal_belanja') is-invalid @enderror" name="minimal_belanja" value="{{ old('minimal_belanja', 0) }}" min="0" required>
                    <small class="text-muted">Isi 0 jika tidak ada minimal belanja.</small>
                    @error('minimal_belanja')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kuota Voucher</label>
                    <input type="number" class="form-control @error('kuota') is-invalid @enderror" name="kuota" value="{{ old('kuota', 0) }}" min="0" required>
                    <small class="text-muted">Isi 0 jika kuota tidak terbatas (unlimited).</small>
                    @error('kuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Mulai Berlaku</label>
                    <input type="datetime-local" class="form-control @error('tanggal_mulai') is-invalid @enderror" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Tanggal Kadaluarsa (Expired)</label>
                    <input type="datetime-local" class="form-control @error('tanggal_selesai') is-invalid @enderror" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-12 mb-4">
                    <label class="form-label fw-bold">Status Voucher</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }}>
                            <label class="form-check-label text-success fw-bold" for="statusAktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="status" id="statusNonaktif" value="nonaktif" {{ old('status') == 'nonaktif' ? 'checked' : '' }}>
                            <label class="form-check-label text-danger fw-bold" for="statusNonaktif">Nonaktif</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-4">
                <a href="{{ route('mitra.voucher.index') }}" class="btn btn-light border px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i> Simpan Voucher</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipePotongan = document.getElementById('tipe_potongan');
        const simbolPotongan = document.getElementById('simbol_potongan');
        const alokasi = document.getElementById('alokasi');
        const produkContainer = document.getElementById('produk_container');

        // Toggle simbol Rp / %
        tipePotongan.addEventListener('change', function() {
            if (this.value === 'nominal') {
                simbolPotongan.textContent = 'Rp';
            } else {
                simbolPotongan.textContent = '%';
            }
        });
        
        // Trigger on load
        tipePotongan.dispatchEvent(new Event('change'));

        // Toggle Produk Container
        alokasi.addEventListener('change', function() {
            if (this.value === 'menu_tertentu') {
                produkContainer.style.display = 'block';
            } else {
                produkContainer.style.display = 'none';
            }
        });
        
        // Trigger on load
        alokasi.dispatchEvent(new Event('change'));
    });
</script>
@endsection
