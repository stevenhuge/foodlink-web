<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\User;
use App\Models\Admin;
use App\Models\Mitra;
use App\Models\LogKeuangan;
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

        // Cek model AlasanBlokirOption (opsional)
        $alasanBlokirOptions = \App\Models\AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('mitra.riwayat.index', compact('transaksis', 'alasanBlokirOptions'));
    }

    public function index2()
    {
        $mitraId = Auth::guard('mitra')->id();
        $transaksis = Transaksi::where('mitra_id', $mitraId)
                            ->where('status_pemesanan', 'paid')
                            ->with('user', 'detailTransaksi.produk')
                            ->orderBy('waktu_pemesanan', 'desc')
                            ->get();

        $alasanBlokirOptions = \App\Models\AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('mitra.pesanan.index', compact('transaksis', 'alasanBlokirOptions'));
    }

    public function show($id)
    {
        // Kode show (jika diperlukan)
    }

    // --- FUNGSI KONFIRMASI (TERIMA PESANAN) ---
    public function konfirmasi($id)
    {
        $mitraId = Auth::guard('mitra')->id();

        try {
            $result = DB::transaction(function () use ($id, $mitraId) {

                // 1. Cari & Lock Transaksi
                $transaksi = Transaksi::where('transaksi_id', $id)
                                     ->where('mitra_id', $mitraId)
                                     ->lockForUpdate()
                                     ->firstOrFail();

                if (strtolower($transaksi->status_pemesanan) != 'paid') {
                    throw new \Exception('Transaksi ini sudah diproses (selesai atau batal).');
                }

                // 2. Cari Mitra & SuperAdmin
                $mitra = Mitra::lockForUpdate()->find($transaksi->mitra_id);
                $superAdmin = Admin::lockForUpdate()->where('role', 'SuperAdmin')->first();

                if (!$superAdmin) {
                    throw new \Exception("Kesalahan Sistem: SuperAdmin tidak ditemukan.");
                }

                // === 3. AMBIL DATA KEUANGAN DARI TRANSAKSI ===
                // Data ini sudah disimpan saat Checkout di Android, jadi PASTI AKURAT
                // sesuai settingan saat user beli (misal: Layanan 500, Pajak 0.5%)

                $biayaLayananUser = $transaksi->biaya_layanan_user;       // Uang dari User (misal 500)
                $potonganPajakMitra = $transaksi->potongan_pajak_mitra;   // Uang dari Mitra (misal 50)
                $pendapatanBersihMitra = $transaksi->pendapatan_bersih_mitra; // Hak Mitra

                // --- LOGIKA JAGA-JAGA (FALLBACK) ---
                // Hanya jalan jika data di database 0 (transaksi lama sebelum update sistem)
                if ($pendapatanBersihMitra <= 0 && $transaksi->total_harga_poin > 0) {
                    // Pakai settingan default database sekarang
                    $persenPajak = \App\Models\Setting::ambil('biaya_mitra_persen', 0.5);
                    $settingBiayaApp = \App\Models\Setting::ambil('biaya_layanan_user', 500);

                    $potonganPajakMitra = ceil($transaksi->total_harga_poin * ($persenPajak / 100));
                    $biayaLayananUser = $settingBiayaApp;
                    $pendapatanBersihMitra = $transaksi->total_harga_poin - $potonganPajakMitra;

                    // Update data transaksi biar tidak 0 lagi
                    $transaksi->update([
                        'biaya_layanan_user' => $biayaLayananUser,
                        'potongan_pajak_mitra' => $potonganPajakMitra,
                        'pendapatan_bersih_mitra' => $pendapatanBersihMitra
                    ]);
                }
                // -----------------------------------

                // 4. HITUNG TOTAL HAK SUPER ADMIN
                // Pemasukan Admin = Biaya Layanan User + Potongan Mitra
                $totalMasukAdmin = $biayaLayananUser + $potonganPajakMitra;

                // 5. EKSEKUSI PINDAH SALDO (The Moment of Truth)
                $mitra->increment('saldo_pemasukan', $pendapatanBersihMitra);
                $superAdmin->increment('saldo_pemasukan', $totalMasukAdmin);

                // 6. CATAT LOG KEUANGAN (Agar muncul di Rincian Pemasukan Admin)

                // A. Log Uang Masuk ke Mitra (Penjualan Bersih)
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => Mitra::class,
                    'penerima_id' => $mitra->mitra_id,
                    'tipe' => 'penjualan_bersih',
                    'jumlah' => $pendapatanBersihMitra,
                    'keterangan' => 'Penjualan bersih produk'
                ]);

                // B. Log Uang Masuk ke Admin (Dari Potongan Mitra)
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => Admin::class,
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'pajak_mitra',
                    'jumlah' => $potonganPajakMitra,
                    'keterangan' => 'Potongan biaya mitra (' . $transaksi->kode_unik_pengambilan . ')'
                ]);

                // C. Log Uang Masuk ke Admin (Dari Biaya Layanan User)
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => Admin::class,
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'biaya_layanan', // Tipe ini penting untuk filter di dashboard
                    'jumlah' => $biayaLayananUser,
                    'keterangan' => 'Biaya layanan user (' . $transaksi->kode_unik_pengambilan . ')'
                ]);

                // 7. Update status transaksi
                $transaksi->status_pemesanan = 'selesai';
                $transaksi->save();

                return $transaksi;
            });

            return redirect()->route('mitra.pesanan.index')
                             ->with('success', 'Transaksi selesai. Saldo masuk ke dompet Anda.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.pesanan.index')
                             ->with('error', 'Gagal konfirmasi: ' . $e->getMessage());
        }
    }

    // --- FUNGSI BATALKAN (TOLAK PESANAN) - INI YANG DIPERBAIKI ---
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

                // === PERBAIKAN LOGIKA REFUND 100% ===

                // Kita kembalikan 'total_harga' karena ini adalah jumlah
                // yang dipotong dari poin user saat Checkout.
                // (Total Harga = Harga Barang + Biaya Layanan)
                $totalRefund = $transaksi->total_harga;

                // Fallback Jaga-jaga (Hanya jika data error/kosong)
                // Kita TIDAK LAGI menghitung 0.2%, tapi menjumlahkan manual.
                if ($totalRefund <= 0) {
                     $totalRefund = $transaksi->total_harga_poin + $transaksi->biaya_layanan_user;
                }

                // Kembalikan poin ke user
                $user->increment('poin_reward', $totalRefund);

                // Kembalikan Stok Produk
                $details = $transaksi->detailTransaksi; // Pastikan relasi ada di model Transaksi
                foreach($details as $detail) {
                    $produk = Produk::find($detail->produk_id);
                    if($produk) {
                        $produk->increment('stok_tersisa', $detail->jumlah);
                        if($produk->stok_tersisa > 0 && $produk->status_produk == 'Habis') {
                            $produk->update(['status_produk' => 'Tersedia']);
                        }
                    }
                }

                $transaksi->status_pemesanan = 'batal';
                $transaksi->save();

                return $totalRefund;
            });

            return redirect()->route('mitra.pesanan.index')
                             ->with('success', 'Transaksi dibatalkan. Poin dikembalikan penuh (' . number_format($result) . ') ke user.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.pesanan.index')
                             ->with('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        $mitraId = Auth::guard('mitra')->id();
        $fileName = 'riwayat_transaksi_mitra_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new RiwayatTransaksiExport($mitraId), $fileName);
    }
}
