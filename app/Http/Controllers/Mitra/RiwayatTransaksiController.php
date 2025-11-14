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

        // Ambil alasan blokir (jika Anda masih menggunakannya di file lain)
        // Jika tidak, baris ini bisa dihapus
        $alasanBlokirOptions = \App\Models\AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('mitra.riwayat.index', compact('transaksis', 'alasanBlokirOptions'));
    }

    public function index2()
    {
        $mitraId = Auth::guard('mitra')->id();
        $transaksis = Transaksi::where('mitra_id', $mitraId)
                            ->with('user', 'detailTransaksi.produk')
                            ->orderBy('waktu_pemesanan', 'desc')
                            ->get();

        // Ambil alasan blokir (jika Anda masih menggunakannya di file lain)
        // Jika tidak, baris ini bisa dihapus
        $alasanBlokirOptions = \App\Models\AlasanBlokirOption::orderBy('alasan_text')->get();

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

                // 1. Cari transaksi, pastikan milik Mitra & status 'paid'
                $transaksi = Transaksi::where('transaksi_id', $id)
                                     ->where('mitra_id', $mitraId)
                                     ->lockForUpdate()
                                     ->firstOrFail();

                if (strtolower($transaksi->status_pemesanan) != 'paid') {
                    throw new \Exception('Transaksi ini sudah diproses (selesai atau batal).');
                }

                // 2. Cari Mitra dan SuperAdmin
                $mitra = $transaksi->mitra; // Relasi sudah ada
                $superAdmin = Admin::lockForUpdate()->where('role', 'SuperAdmin')->first();
                if (!$superAdmin) {
                    throw new \Exception("Kesalahan Sistem: SuperAdmin tidak ditemukan.");
                }

                // 3. PINDAHKAN SALDO
                // Tambah Saldo Mitra
                $mitra->increment('saldo_pemasukan', $transaksi->pendapatan_bersih_mitra);

                // Tambah Saldo SuperAdmin (Pajak + Biaya Layanan)
                $pendapatanSuperAdmin = $transaksi->potongan_pajak_mitra + $transaksi->biaya_layanan_user;
                $superAdmin->increment('saldo_pemasukan', $pendapatanSuperAdmin);

                // 4. BUAT LOG KEUANGAN (Baru pindah ke sini)
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => get_class($mitra),
                    'penerima_id' => $mitra->mitra_id,
                    'tipe' => 'penjualan_bersih',
                    'jumlah' => $transaksi->pendapatan_bersih_mitra
                ]);
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => get_class($superAdmin),
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'pajak_mitra',
                    'jumlah' => $transaksi->potongan_pajak_mitra
                ]);
                LogKeuangan::create([
                    'transaksi_id' => $transaksi->transaksi_id,
                    'penerima_type' => get_class($superAdmin),
                    'penerima_id' => $superAdmin->admin_id,
                    'tipe' => 'biaya_layanan',
                    'jumlah' => $transaksi->biaya_layanan_user
                ]);

                // 5. Update status transaksi
                $transaksi->status_pemesanan = 'selesai';
                $transaksi->save();

                return $transaksi; // Berhasil
            });

            return redirect()->route('mitra.riwayat.index')
                             ->with('success', 'Transaksi ' . $result->kode_unik_pengambilan . ' telah dikonfirmasi (Selesai). Pemasukan telah ditambahkan ke saldo Anda.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.riwayat.index')
                             ->with('error', 'Gagal konfirmasi transaksi: ' . $e->getMessage());
        }
    }

    /**
     * === 3. FUNGSI BARU UNTUK MEMBATALKAN PESANAN ===
     */
    public function batalkan($id)
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
                    throw new \Exception('Transaksi ini tidak dapat dibatalkan.');
                }

                // 2. Cari User
                $user = User::lockForUpdate()->findOrFail($transaksi->user_id);

                // 3. KEMBALIKAN POIN KE USER (PERBAIKAN)
                // Kembalikan harga produk DAN biaya layanan
                $totalRefund = $transaksi->total_harga_poin + $transaksi->biaya_layanan_user;
                $user->increment('poin_reward', $totalRefund);

                // 4. UBAH STATUS TRANSAKSI
                $transaksi->status_pemesanan = 'batal';
                $transaksi->save();

                // (Opsional: Kembalikan Stok Produk)
                // foreach ($transaksi->detailTransaksi as $detail) {
                //     $detail->produk()->increment('stok_tersisa', $detail->jumlah);
                // }

                return $transaksi;
            });

            return redirect()->route('mitra.riwayat.index')
                             ->with('success', 'Transaksi ' . $result->kode_unik_pengambilan . ' berhasil dibatalkan. Poin telah dikembalikan ke user.');

        } catch (\Exception $e) {
            return redirect()->route('mitra.riwayat.index')
                             ->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }


    public function exportExcel()
    {
        $mitraId = Auth::guard('mitra')->id();

        $fileName = 'riwayat_transaksi_mitra_' . date('Y-m-d') . '.xlsx';

        // Panggil Class Export yang tadi kita buat
        return Excel::download(new RiwayatTransaksiExport($mitraId), $fileName);
    }
}
