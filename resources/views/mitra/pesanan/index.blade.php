@extends('mitra.layouts.app')
{{-- Sesuaikan dengan layout Mitra Anda --}}

@section('title', 'Riwayat Transaksi')

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
                <table class="table table-hover table-striped align-middle caption-top">
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
                            {{-- Ubah nilai status ke huruf kecil HANYA untuk perbandingan --}}
                            @php
                                $status = strtolower($transaksi->status_pemesanan);
                            @endphp

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

                                <td class="text-nowrap">

                                    @if($status == 'paid')
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
                                        @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada riwayat transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> </div> </div> @endsection
