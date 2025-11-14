@extends('admin.layouts.app')
{{-- Sesuaikan dengan layout Admin Anda --}}

@section('title', 'Review Penarikan Mitra')

{{-- CSS untuk DataTables --}}
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Review Penarikan Dana Mitra</h2>
        <a href="{{ route('admin.pemasukan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Kembali ke Pemasukan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="reviewPenarikanTable">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Diajukan</th>
                            <th>Nama Mitra</th>
                            <th>Jumlah (Poin)</th>
                            <th>Rekening Tujuan</th>
                            <th>Status</th>
                            <th style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penarikanMitra as $penarikan)
                            <tr>
                                <td>{{ $penarikan->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <strong>{{ $penarikan->penarikanable->nama_mitra ?? 'Mitra Dihapus' }}</strong>
                                </td>
                                <td>{{ number_format($penarikan->jumlah) }}</td>
                                <td class="small">
                                    {{ $penarikan->rekeningBank->nama_bank }}
                                    <br>
                                    {{ $penarikan->rekeningBank->nomor_rekening }}
                                    <br>
                                    (a.n {{ $penarikan->rekeningBank->nama_pemilik }})
                                </td>
                                <td>
                                    @if($penarikan->status == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($penarikan->status == 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($penarikan->status == 'Ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $penarikan->status }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    @if($penarikan->status == 'Pending')
                                        <form action="{{ route('admin.review.penarikan.update', $penarikan->penarikan_id) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Anda yakin ingin SETUJUI penarikan ini? (Pastikan Anda sudah transfer manual)');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $penarikan->penarikan_id }}" title="Tolak">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    @else
                                        <span class="text-muted fst-italic">Telah diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada permintaan penarikan dana dari mitra.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($penarikanMitra as $penarikan)
        @if($penarikan->status == 'Pending')
        <div class="modal fade" id="rejectModal-{{ $penarikan->penarikan_id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $penarikan->penarikan_id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel-{{ $penarikan->penarikan_id }}">Tolak Penarikan Mitra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.review.penarikan.update', $penarikan->penarikan_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="action" value="reject">
                        <div class="modal-body">
                            <p>Anda akan menolak penarikan sebesar <strong>{{ number_format($penarikan->jumlah) }} Poin</strong>
                               dari <strong>{{ $penarikan->penarikanable->nama_mitra ?? 'Mitra' }}</strong>.
                               Saldo akan dikembalikan ke Mitra.
                            </p>
                            <div class="mb-3">
                                <label for="catatan_admin_{{ $penarikan->penarikan_id }}" class="form-label">Alasan Penolakan (Opsional):</label>
                                <textarea name="catatan_admin" id="catatan_admin_{{ $penarikan->penarikan_id }}" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Tolak Penarikan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach

@endsection

{{-- Script JS untuk DataTables --}}
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#reviewPenarikanTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data",
                    "paginate": { "next": "Berikutnya", "previous": "Sebelumnya" }
                }
            });
        });
    </script>
@endsection
