<?php
// app/Http/Controllers/Mitra/RiwayatTransaksiController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\User;
use App\Models\Admin;
use App\Models\Mitra;
use App\Models\LogKeuangan;
use App\Models\AlasanBlokirOption; // Pastikan ini ada
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\RiwayatTransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatTransaksiController extends Controller
{
    public function index()
    {
        $mitraId = Auth::guard('mitra')->id();
        $transaksis = Transaksi::where('mitra_id', $mitraId)
                            ->with('user', 'detailTransaksi.produk')
                            ->orderBy('waktu_pemesanan', 'desc')
                            ->get();

        $alasanBlokirOptions = AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('mitra.riwayat.index', compact('transaksis', 'alasanBlokirOptions'));
    }

    public function index2()
    {
        $mitraId = Auth::guard('mitra')->id();
        $transaksis = Transaksi::where('mitra_id', $mitraId)
                            ->where('status_pemesanan', 'paid') // Filter 'paid'
                            ->with('user', 'detailTransaksi.produk')
                            ->orderBy('waktu_pemesanan', 'desc')
                            ->get();

        $alasanBlokirOptions = AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('mitra.pesanan.index', compact('transaksis', 'alasanBlokirOptions'));
    }

    public function show($id)
    {
        // ... (Fungsi show() Anda tidak berubah)
    }

    public function konfirmasi($id)
    {
        $mitraId = Auth::guard('mitra')->id();

        try {
            $result = DB::transaction(function () use ($id, $mitraId) {

                // 1. Cari transaksi
                $transaksi = Transaksi::where('transaksi_id', $id)
                                     ->where('mitra_id', $mitraId)
                                     ->lockForUpdate()
                                     ->firstOrFail();

                if (strtolower($transaksi->status_pemesanan) != 'paid') {
                    throw new \Exception('Transaksi ini sudah diproses (selesai atau batal).');
                }

                // 2. Cari Mitra dan SuperAdmin
                $mitra = Mitra::lockForUpdate()->find($transaksi->mitra_id);
                $superAdmin = Admin::lockForUpdate()->where('role', 'SuperAdmin')->first();
                if (!$superAdmin) {
                    throw new \Exception("Kesalahan Sistem: SuperAdmin tidak ditemukan.");
                }

                // === 3. LOGIKA KEUANGAN DENGAN FALLBACK ===

                // Ambil nilai dari DB
                $pendapatanBersih = $transaksi->pendapatan_bersih_mitra;
                $potonganPajak = $transaksi->potongan_pajak_mitra;
                $biayaLayanan = $transaksi->biaya_layanan_user;

                // CEK APAKAH INI DATA LAMA (NILAINYA 0)
                // Kita cek berdasarkan 'pendapatanBersih'. Jika 0, kita hitung ulang semua.
                if ($pendapatanBersih <= 0 && $transaksi->total_harga_poin > 0)
                {
                    // Ini adalah "DATA LAMA". Hitung ulang manual.
                    $potonganPajak = (int) ceil($transaksi->total_harga_poin * 0.005);
                    $pendapatanBersih = $transaksi->total_harga_poin - $potonganPajak;
                    $biayaLayanan = (int) ceil($transaksi->total_harga_poin * 0.002);
                }

                $pendapatanSuperAdmin = $potonganPajak + $biayaLayanan;

                // 4. PINDAHKAN SALDO (Gunakan nilai yang sudah divalidasi)
                $mitra->increment('saldo_pemasukan', $pendapatanBersih);
                $superAdmin->increment('saldo_pemasukan', $pendapatanSuperAdmin);

                // 5. BUAT LOG KEUANGAN
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => Mitra::class,
                    'penerima_id' => $mitra->mitra_id,
                    'tipe' => 'penjualan_bersih',
                    'jumlah' => $pendapatanBersih // Pakai nilai terhitung
                ]);
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => Admin::class,
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'pajak_mitra',
                    'jumlah' => $potonganPajak // Pakai nilai terhitung
                ]);
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => Admin::class,
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'biaya_layanan',
                    'jumlah' => $biayaLayanan // Pakai nilai terhitung
                ]);

                // 6. Update status transaksi
                $transaksi->status_pemesanan = 'selesai';
                $transaksi->save();

                return $transaksi; // Berhasil
            });

            return redirect()->route('mitra.pesanan.index')
                             ->with('success', 'Transaksi ' . $result->kode_unik_pengambilan . ' telah dikonfirmasi. Pemasukan telah ditambahkan ke saldo.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.pesanan.index')
                             ->with('error', 'Gagal konfirmasi transaksi: ' . $e->getMessage());
        }
    }

    public function batalkan($id)
    {
        $mitraId = Auth::guard('mitra')->id();
        try {
            $result = DB::transaction(function () use ($id, $mitraId) {

                $transaksi = Transaksi::where('transaksi_id', $id)
                                     ->where('mitra_id', $mitraId)
                                     ->lockForUpdate()
                                     ->firstOrFail();

                if (strtolower($transaksi->status_pemesanan) != 'paid') {
                    throw new \Exception('Transaksi ini tidak dapat dibatalkan.');
                }

                $user = User::lockForUpdate()->findOrFail($transaksi->user_id);

                // HITUNG ULANG BIAYA LAYANAN (JIKA DATA LAMA)
                $biayaLayanan = $transaksi->biaya_layanan_user;
                if ($biayaLayanan <= 0 && $transaksi->total_harga_poin > 0) {
                     $biayaLayanan = (int) ceil($transaksi->total_harga_poin * 0.002);
                }

                $totalRefund = $transaksi->total_harga_poin + $biayaLayanan;
                $user->increment('poin_reward', $totalRefund);

                $transaksi->status_pemesanan = 'batal';
                $transaksi->save();

                return $transaksi;
            });

            return redirect()->route('mitra.pesanan.index')
                             ->with('success', 'Transaksi ' . $result->kode_unik_pengambilan . ' berhasil dibatalkan. Poin telah dikembalikan ke user.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.pesanan.index')
                             ->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        $fileName = 'riwayat_transaksi_mitra_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new RiwayatTransaksiExport, $fileName);
    }
}
