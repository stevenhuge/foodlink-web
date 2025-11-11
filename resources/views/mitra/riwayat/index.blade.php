@extends('mitra.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <h1>Riwayat Transaksi</h1>
    <p>Daftar pesanan yang masuk melalui aplikasi mobile.</p>

    @if (session('success'))
        <div style="color: green; background: #e0f8e0; padding: 10px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="color: red; background: #f8e0e0; padding: 10px; margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
        th { background-color: #f4f4f4; }
        .details { margin-left: 20px; margin-top: 5px; }
        .status-paid { color: red; font-weight: bold; }
        .status-selesai { color: green; font-weight: bold; }
        .action-button { background-color: #007bff; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; }
    </style>

    <table>
        <thead>
            <tr>
                <th>Waktu Pesan</th>
                <th>Kode</th>
                <th>Pembeli</th>
                <th>Detail</th>
                <th>Total Poin</th>
                <th>Status Tampil</th>
                <th>Aksi</th>
                <th>(Debug Status DB)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $transaksi)
                {{-- Ubah nilai status ke huruf kecil HANYA untuk perbandingan --}}
                @php
                    $status = strtolower($transaksi->status_pemesanan);
                @endphp

                <tr>
                    <td>{{ $transaksi->waktu_pemesanan->format('d M Y, H:i') }}</td>
                    <td><strong>{{ $transaksi->kode_unik_pengambilan }}</strong></td>
                    <td>{{ $transaksi->user->nama_lengkap ?? 'User Dihapus' }}</td>
                    <td>
                        @foreach($transaksi->detailTransaksi as $detail)
                            <div class="details">
                                {{ $detail->jumlah }}x {{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}
                            </div>
                        @endforeach
                    </td>
                    <td>{{ number_format($transaksi->total_harga_poin) }} Poin</td>

                    {{-- === PERBAIKAN DI SINI === --}}
                    {{-- Gunakan variabel $status (huruf kecil) untuk perbandingan --}}
                    <td>
                        @if($status == 'paid')
                            <span class="status-paid">Belum Diambil</span>
                        @elseif($status == 'selesai')
                            <span class="status-selesai">✔ Selesai</span>
                        @else
                            {{-- Tampilkan nilai asli jika statusnya lain --}}
                            <span style="text-transform: capitalize;">{{ $transaksi->status_pemesanan ?? 'NULL' }}</span>
                        @endif
                    </td>

                    {{-- === PERBAIKAN DI SINI === --}}
                    <td>
                        @if($status == 'paid')
                            <form action="{{ route('mitra.riwayat.konfirmasi', $transaksi->transaksi_id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin konfirmasi pesanan ini selesai?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="action-button">
                                    Konfirmasi (Selesai)
                                </button>
                            </form>
                        @else
                            -
                        @endif
                    </td>

                    <td style="background-color: #fffbcc; color: #666; font-family: monospace;">
                        {{ $transaksi->status_pemesanan ?? 'NULL' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada riwayat transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@endsection
