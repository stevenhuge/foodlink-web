@extends('admin.layouts.app')
{{-- Sesuaikan dengan layout Admin Anda --}}

@section('title', 'Manajemen Pemasukan')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .tab-content { padding-top: 1rem; }
    </style>
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Pemasukan</h2>
        <a href="{{ route('admin.review.penarikan.index') }}" class="btn btn-info shadow-sm">
            <i class="fas fa-tasks fa-sm me-1"></i> Review Penarikan Mitra
        </a>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Saldo Pemasukan (Pajak & Layanan)</h6>
                </div>
                <div class="card-body">
                    <h1 class="display-4 text-gray-800">{{ number_format($saldoSaatIni) }} Poin</h1>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Pemasukan (Kotor)</span>
                        <strong>{{ number_format($totalPemasukan) }} Poin</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Total Telah Ditarik</span>
                        <strong class="text-danger">- {{ number_format($totalDitarik) }} Poin</strong>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tarik Dana (Pribadi)</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pemasukan.tarik') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Penarikan (Poin)</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control"
                                   max="{{ $saldoSaatIni }}" min="20000"
                                   placeholder="Min. 20.000" required>
                            <div class="form-text">Saldo tersedia: {{ number_format($saldoSaatIni) }} Poin</div>
                        </div>
                        <div class="mb-3">
                            <label for="rekening_bank_id" class="form-label">Tarik Ke Rekening</label>
                            <select name="rekening_bank_id" id="rekening_bank_id" class="form-select" required>
                                @if($rekeningBank->isEmpty())
                                    <option value="" disabled selected>Anda belum menambah rekening bank</option>
                                @else
                                    <option value="" disabled selected>-- Pilih Rekening --</option>
                                    @foreach($rekeningBank as $rekening)
                                        <option value="{{ $rekening->rekening_id }}">
                                            {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }} (a.n {{ $rekening->nama_pemilik }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="form-text">
                                <a href="{{ route('admin.rekening-bank.index') }}">Kelola Rekening Bank Anda</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" {{ $rekeningBank->isEmpty() || $saldoSaatIni < 20000 ? 'disabled' : '' }}>
                            Tarik Dana
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="pemasukanTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="rincian-tab" data-bs-toggle="tab" data-bs-target="#rincian-pemasukan-tab-pane" type="button" role="tab" aria-controls="rincian-pemasukan-tab-pane" aria-selected="true">
                                Rincian Pemasukan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat-penarikan-tab-pane" type="button" role="tab" aria-controls="riwayat-penarikan-tab-pane" aria-selected="false">
                                Riwayat Penarikan Anda
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="pemasukanTabContent">

                        <div class="tab-pane fade show active" id="rincian-pemasukan-tab-pane" role="tabpanel" aria-labelledby="rincian-tab" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-striped" id="rincianPemasukanTable" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Tipe Pemasukan</th>
                                            <th>Jumlah (Poin)</th>
                                            <th>Sumber Pemasukan</th> </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rincianPemasukan as $log)
                                            <tr>
                                                <td>{{ $log->created_at->format('d M Y, H:i') }}</td>
                                                <td>
                                                    @if($log->tipe == 'pajak_mitra')
                                                        <span class="badge bg-info">Pajak Mitra (0.5%)</span>
                                                    @elseif($log->tipe == 'biaya_layanan')
                                                        <span class="badge bg-primary">Biaya Layanan (0.2%)</span>
                                                    @elseif($log->tipe == 'pajak_penarikan_mitra')
                                                        <span class="badge bg-warning text-dark">Pajak Penarikan (2.5%)</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $log->tipe }}</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-success">+ {{ number_format($log->jumlah) }}</td>

                                                {{-- === PERBAIKAN LOGIKA TAMPILAN DI SINI === --}}
                                                <td class="small">
                                                    @if ($log->transaksi)
                                                        Trx: {{ $log->transaksi->kode_unik_pengambilan }}
                                                        <br>
                                                        (User: {{ $log->transaksi->user->nama_lengkap ?? 'N/A' }})

                                                    @elseif ($log->penarikanDana)
                                                        Penarikan ID: {{ $log->penarikanDana->penarikan_id }}
                                                        <br>
                                                        (Mitra: {{ $log->penarikanDana->penarikanable->nama_mitra ?? 'N/A' }})

                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                {{-- === AKHIR PERBAIKAN === --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="riwayat-penarikan-tab-pane" role="tabpanel" aria-labelledby="riwayat-tab" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="riwayatPenarikanTable" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah (Poin)</th>
                                            <th>Rekening Tujuan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riwayatPenarikan as $penarikan)
                                            <tr>
                                                <td>{{ $penarikan->created_at->format('d M Y') }}</td>
                                                <td>{{ number_format($penarikan->jumlah) }}</td>
                                                <td class="small">{{ $penarikan->rekeningBank->nama_bank }} - {{ $penarikan->rekeningBank->nomor_rekening }}</td>
                                                <td>
                                                    @if($penarikan->status == 'Pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($penarikan->status == 'Selesai')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @elseif($penarikan->status == 'Ditolak')
                                                        <span class="badge bg-danger" title="Alasan: {{ $penarikan->catatan_admin }}">Ditolak</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $penarikan->status }}</span>
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
                "language": { "search": "Cari:", /*...dll...*/ }
            });
            var riwayatTable = $('#riwayatPenarikanTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": { "search": "Cari:", /*...dll...*/ }
            });
            $('button[data-bs-target="#rincian-pemasukan-tab-pane"]').on('shown.bs.tab', function(e) {
                rincianTable.columns.adjust().draw();
            });
            $('button[data-bs-target="#riwayat-penarikan-tab-pane"]').on('shown.bs.tab', function(e) {
                riwayatTable.columns.adjust().draw();
            });
        });
    </script>
@endsection
