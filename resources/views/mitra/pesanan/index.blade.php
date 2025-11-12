@extends('mitra.layouts.app')
{{-- Sesuaikan dengan layout Mitra Anda --}}

@section('title', 'Pesanan Masuk') {{-- Judul sudah benar --}}

{{-- === 1. PERUBAIKAN: Tambahkan CSS DataTables === --}}
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endsection
{{-- ============================================= --}}

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Pesanan Masuk</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">

            @if (session('success'))
                {{-- ... (Pesan sukses Anda) ... --}}
            @endif
            @if (session('error'))
                {{-- ... (Pesan error Anda) ... --}}
            @endif

            <div class="table-responsive">

                {{-- === 2. PERUBAIKAN: Tambahkan ID unik pada tabel === --}}
                <table class="table table-hover table-striped align-middle caption-top" id="pesananTable">
                {{-- ================================================= --}}

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
                        @forelse ($transaksis as $transaksi)
                            @php
                                $status = strtolower($transaksi->status_pemesanan);
                            @endphp

                            {{-- Logika @if ($status === 'paid') Anda sudah benar untuk halaman ini --}}
                            @if ($status === 'paid')
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
                                        {{-- Ini akan selalu "Belum Diambil" karena @if di atas --}}
                                        <span class="badge bg-warning text-dark">Belum Diambil</span>
                                    </td>

                                    <td class="text-nowrap">
                                        {{-- ... (Tombol aksi Anda sudah benar) ... --}}
                                        <form action="{{ route('mitra.riwayat.konfirmasi', $transaksi->transaksi_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin pesanan ini SUDAH DIAMBIL?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Konfirmasi (Selesai)">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('mitra.riwayat.batalkan', $transaksi->transaksi_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin MEMBATALKAN pesanan ini? Poin akan dikembalikan ke user.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Batalkan Pesanan">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    {{--
                                        Kita ubah pesannya sedikit karena @forelse
                                        mungkin bingung dengan @if di dalamnya
                                    --}}
                                    <i class="fas fa-info-circle me-2"></i> Tidak ada pesanan masuk saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
            {{-- === 3. PERUBAIKAN: Gunakan ID "pesananTable" === --}}
            $('#pesananTable').DataTable({
            {{-- =============================================== --}}
                "order": [[ 0, "desc" ]],
                "language": {
                    "search": "Cari:",
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
