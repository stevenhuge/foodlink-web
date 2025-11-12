@extends('mitra.layouts.app')

@section('title', 'Riwayat Transaksi')

{{-- 1. CSS Diperlukan untuk DataTables --}}
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection


@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Riwayat Transaksi Masuk</h2>
        <a href="{{ route('mitra.riwayat.export.excel') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-file-excel fa-sm me-1"></i> Ekspor ke Excel
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">

            @if (session('success'))
                @endif
            @if (session('error'))
                @endif

            <div class="table-responsive">

                <table class="table table-hover table-striped align-middle caption-top" id="riwayatTable">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Pesan</th>
                            <th>Kode</th>
                            <th>Pembeli</th>
                            <th>Detail Produk</th>
                            <th>Total Poin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksis as $transaksi)
                            {{-- ... (Looping data Anda seperti sebelumnya) ... --}}
                            @php
                                $status = strtolower($transaksi->status_pemesanan);
                            @endphp

                            @if ($status === "selesai" || $status === "dibatalkan")
                                <tr>
                                <td>{{ $transaksi->waktu_pemesanan->format('d M Y, H:i') }}</td>
                                <td><strong>{{ $transaksi->kode_unik_pengambilan }}</strong></td>
                                <td>{{ $transaksi->user->nama_lengkap ?? 'User Dihapus' }}</td>
                                <td>
                                    @foreach($transaksi->detailTransaksi as $detail)
                                        <div class="details small">
                                            {{ $detail->jumlah }}x {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ number_format($transaksi->total_harga_poin) }}</td>
                                <td>
                                    @if($status == 'paid')
                                        <span class="badge bg-warning text-dark">Belum Diambil</span>
                                    @elseif($status == 'selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($status == 'batal')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-secondary" style="text-transform: capitalize;">{{ $transaksi->status_pemesanan }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div> </div> </div> @endsection

{{-- 2. Script JS WAJIB untuk mengaktifkan DataTables (Search/Sort) --}}
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Script ini yang memanggil DataTables
        $(document).ready(function() {
            $('#riwayatTable').DataTable({
                "order": [[ 0, "desc" ]],
                // Bagian "language" ini yang menampilkan tulisan "Cari:"
                "language": {
                    "search": "Cari:", // <-- INI FITUR PENCARIANNYA
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "paginate": {
                        "first":      "Pertama",
                        "last":       "Terakhir",
                        "next":       "Berikutnya",
                        "previous":   "Sebelumnya"
                    },
                }
            });
        });
    </script>
@endsection
