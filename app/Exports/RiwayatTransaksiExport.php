<?php
// app/Exports/RiwayatTransaksiExport.php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RiwayatTransaksiExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $mitraId;

    /**
     * Kita gunakan __construct untuk menerima ID Mitra dari Controller
     */
    public function __construct(int $mitraId)
    {
        $this->mitraId = $mitraId;
    }

    /**
     * Query untuk mengambil data dari database.
     */
    public function query()
    {
        return Transaksi::query()
            ->where('mitra_id', $this->mitraId)
            ->with('user', 'detailTransaksi.produk') // Ambil relasi
            ->orderBy('waktu_pemesanan', 'desc');
    }

    /**
     * Menentukan judul (header) kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'Waktu Pesan',
            'Kode Pengambilan',
            'Nama Pembeli',
            'Email Pembeli',
            'Total Poin',
            'Status',
            'Detail Produk',
        ];
    }

    /**
     * Memetakan data dari $transaksi ke kolom Excel.
     * Ini agar kita bisa menampilkan data relasi.
     */
    public function map($transaksi): array
    {
        // Ubah status 'paid' menjadi 'Belum Diambil'
        $status = $transaksi->status_pemesanan;
        if (strtolower($status) == 'paid') {
            $status = 'Belum Diambil';
        }

        // Gabungkan detail produk menjadi satu string
        $detailProduk = '';
        foreach ($transaksi->detailTransaksi as $detail) {
            $namaProduk = $detail->produk->nama_produk ?? 'Produk Dihapus';
            $detailProduk .= $detail->jumlah . 'x ' . $namaProduk . '; ';
        }

        return [
            $transaksi->waktu_pemesanan->format('d M Y, H:i'),
            $transaksi->kode_unik_pengambilan,
            $transaksi->user->nama_lengkap ?? 'User Dihapus',
            $transaksi->user->email ?? '-',
            $transaksi->total_harga_poin,
            ucfirst($status),
            rtrim($detailProduk, '; '), // Hapus '; ' terakhir
        ];
    }
}
