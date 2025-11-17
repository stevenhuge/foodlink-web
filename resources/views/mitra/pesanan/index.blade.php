@extends('mitra.layouts.app')
{{-- Sesuaikan dengan layout Mitra Anda --}}

@section('title', 'Pesanan Masuk')

{{-- 1. CSS untuk DataTables --}}
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Pesanan Masuk</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">

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
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle caption-top" id="pesananTable">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu Pesan</th>
                            <th>Kode Pengambilan</th>
                            <th>Nama Pembeli</th>
                            <th>Detail Produk</th>
                            <th>Total Poin</th>
                            <th>Status</th>
                            <th scope="col" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{--
                          - Kita gunakan @foreach (bukan @forelse) untuk menghindari error DataTables
                          - Kita tidak perlu @if (status == 'paid') karena Controller (index2) sudah memfilternya
                        --}}
                        @foreach ($transaksis as $transaksi)
                            <tr>
                                <td>{{ $transaksi->waktu_pemesanan->format('d M Y, H:i') }}</td>
                                <td><strong>{{ $transaksi->kode_unik_pengambilan }}</strong></td>
                                <td>{{ $transaksi->user->nama_lengkap ?? 'User Dihapus' }}</td>
                                <td>
                                    {{-- Loop detail produk --}}
                                    @foreach($transaksi->detailTransaksi as $detail)
                                        <div class="details small">
                                            {{ $detail->jumlah }}x {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ number_format($transaksi->total_harga_poin) }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">Belum Diambil</span>
                                </td>
                                <td class="text-nowrap">
                                    {{-- Tombol Konfirmasi --}}
                                    <form action="{{ route('mitra.riwayat.konfirmasi', $transaksi->transaksi_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin pesanan ini SUDAH DIAMBIL?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Konfirmasi (Selesai)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    {{-- Tombol Batalkan --}}
                                    <form action="{{ route('mitra.riwayat.batalkan', $transaksi->transaksi_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin MEMBATALKAN pesanan ini? Poin akan dikembalikan ke user.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Batalkan Pesanan">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> </div> </div> @endsection {{-- Ini adalah penutup @section('content') --}}


{{-- 2. Skrip JS untuk DataTables --}}
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pesananTable').DataTable({
                "order": [[ 0, "desc" ]],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada pesanan yang perlu dikonfirmasi.",
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

{{-- 3. Skrip JS untuk DataTables --}}
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
