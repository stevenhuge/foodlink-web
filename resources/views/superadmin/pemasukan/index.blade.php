@extends('admin.layouts.app')

@section('title', 'Manajemen Pemasukan')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .tab-content { padding-top: 1rem; }
        .badge-lg { font-size: 0.9em; padding: 8px 12px; }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Pemasukan</h2>
        <a href="{{ route('admin.review.penarikan.index') }}" class="btn btn-info shadow-sm">
            <i class="fas fa-tasks fa-sm me-1"></i> Review Penarikan Mitra
        </a>
    </div>

    {{-- Alert Success/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Gagal!</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- KOLOM KIRI: INFO SALDO & FORM TARIK --}}
        <div class="col-lg-5">
            {{-- Card Saldo --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-wallet me-2"></i>Dompet SuperAdmin</h6>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted mb-1">Saldo Tersedia (Siap Ditarik)</p>
                    <h1 class="display-5 fw-bold text-gray-800">{{ number_format($saldoSaatIni) }}</h1>
                    <span class="badge bg-light text-dark border">IDR / Poin</span>

                    <hr class="my-4">

                    <div class="row text-start">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block">Total Pemasukan (Bersih)</small>
                            <strong class="text-success h5">+ {{ number_format($totalPemasukan) }}</strong>
                        </div>
                        <div class="col-6 ps-4">
                            <small class="text-muted d-block">Total Telah Ditarik</small>
                            <strong class="text-danger h5">- {{ number_format($totalDitarik) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Form Tarik Dana --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-bill-wave me-2"></i>Tarik Dana (Pribadi)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pemasukan.tarik') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="jumlah" class="form-label fw-bold">Jumlah Penarikan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="jumlah" id="jumlah" class="form-control"
                                       max="{{ $saldoSaatIni }}" min="20000"
                                       placeholder="Min. 20.000" required>
                            </div>
                            <div class="form-text text-end">Maksimal: {{ number_format($saldoSaatIni) }}</div>
                        </div>
                        <div class="mb-4">
                            <label for="rekening_bank_id" class="form-label fw-bold">Ke Rekening</label>
                            <select name="rekening_bank_id" id="rekening_bank_id" class="form-select" required>
                                @if($rekeningBank->isEmpty())
                                    <option value="" disabled selected>-- Anda belum punya rekening --</option>
                                @else
                                    <option value="" disabled selected>-- Pilih Rekening Tujuan --</option>
                                    @foreach($rekeningBank as $rekening)
                                        <option value="{{ $rekening->rekening_id }}">
                                            {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }} ({{ $rekening->nama_pemilik }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="mt-2 text-end">
                                <a href="{{ route('admin.rekening-bank.index') }}" class="text-decoration-none small"><i class="fas fa-plus-circle"></i> Tambah Rekening Baru</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" {{ $rekeningBank->isEmpty() || $saldoSaatIni < 20000 ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane me-2"></i> Ajukan Penarikan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TABEL RINCIAN --}}
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="pemasukanTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold" id="rincian-tab" data-bs-toggle="tab" data-bs-target="#rincian-pemasukan-tab-pane" type="button" role="tab">
                                <i class="fas fa-list-ul me-2"></i>Rincian Mutasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat-penarikan-tab-pane" type="button" role="tab">
                                <i class="fas fa-history me-2"></i>Riwayat Penarikan
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="pemasukanTabContent">

                        {{-- TAB 1: RINCIAN PEMASUKAN --}}
                        <div class="tab-pane fade show active" id="rincian-pemasukan-tab-pane" role="tabpanel" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="rincianPemasukanTable" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                            <th>Ref</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rincianPemasukan as $log)
                                            <tr>
                                                <td class="small text-muted">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                                <td>
                                                    {{-- LOGIKA LABEL DINAMIS (Tanpa Hardcode Angka) --}}
                                                    @if($log->tipe == 'pajak_mitra')
                                                        <span class="badge bg-info text-dark">Potongan Mitra</span>
                                                    @elseif($log->tipe == 'biaya_layanan')
                                                        <span class="badge bg-primary">Biaya Layanan User</span>
                                                    @elseif($log->tipe == 'penarikan_saldo')
                                                        <span class="badge bg-danger">Penarikan Dana</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $log->tipe)) }}</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold {{ $log->jumlah < 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ $log->jumlah < 0 ? '-' : '+' }} {{ number_format(abs($log->jumlah)) }}
                                                </td>
                                                <td class="small">
                                                    @if ($log->transaksi)
                                                        <a href="#" class="text-decoration-none">#{{ $log->transaksi->kode_unik_pengambilan }}</a>
                                                        <div class="text-muted x-small">User: {{ $log->transaksi->user->nama_lengkap ?? '-' }}</div>
                                                    @elseif ($log->penarikanDana)
                                                        <span class="text-muted">WD #{{ $log->penarikanDana->penarikan_id }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- TAB 2: RIWAYAT PENARIKAN --}}
                        <div class="tab-pane fade" id="riwayat-penarikan-tab-pane" role="tabpanel" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="riwayatPenarikanTable" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Tujuan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatPenarikan as $penarikan)
                                            <tr>
                                                <td>{{ $penarikan->created_at->format('d M Y') }}</td>
                                                <td class="fw-bold">{{ number_format($penarikan->jumlah) }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $penarikan->rekeningBank->nama_bank }}</div>
                                                    <div class="small text-muted">{{ $penarikan->rekeningBank->nomor_rekening }}</div>
                                                </td>
                                                <td>
                                                    @if($penarikan->status == 'Selesai')
                                                        <span class="badge bg-success rounded-pill"><i class="fas fa-check me-1"></i> Berhasil</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill">{{ $penarikan->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var rincianTable = $('#rincianPemasukanTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": { "search": "Cari Mutasi:" }
            });
            var riwayatTable = $('#riwayatPenarikanTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": { "search": "Cari Penarikan:" }
            });

            // Fix DataTable Header width issue inside Tabs
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                rincianTable.columns.adjust().draw();
                riwayatTable.columns.adjust().draw();
            });
        });
    </script>
@endsection
