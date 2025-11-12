<?php
// app/Http/Controllers/Mitra/RiwayatTransaksiController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\User; // <-- 1. TAMBAHKAN IMPORT USER
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- 2. TAMBAHKAN IMPORT DB

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
        // ... (Fungsi konfirmasi() Anda tidak berubah)
        // Pastikan Anda menggunakan strtolower() di sini
        $mitraId = Auth::guard('mitra')->id();
        $transaksi = Transaksi::where('transaksi_id', $id)
                             ->where('mitra_id', $mitraId)
                             ->firstOrFail();
        if (strtolower($transaksi->status_pemesanan) == 'paid') {
            $transaksi->status_pemesanan = 'selesai';
            $transaksi->save();
            return redirect()->route('mitra.riwayat.index')
                             ->with('success', 'Transaksi ' . $transaksi->kode_unik_pengambilan . ' telah dikonfirmasi (Selesai).');
        }
        return redirect()->route('mitra.riwayat.index')
                         ->with('error', 'Transaksi ini sudah dikonfirmasi sebelumnya.');
    }

    /**
     * === 3. FUNGSI BARU UNTUK MEMBATALKAN PESANAN ===
     */
    public function batalkan($id)
    {
        $mitraId = Auth::guard('mitra')->id();

        // Kita gunakan DB Transaction untuk memastikan SEMUA query berhasil
        // atau tidak sama sekali (mencegah error poin hilang tapi status tidak berubah)
        try {

            $result = DB::transaction(function () use ($id, $mitraId) {

                // 1. Cari transaksi, pastikan milik Mitra & lock untuk update
                $transaksi = Transaksi::where('transaksi_id', $id)
                                     ->where('mitra_id', $mitraId)
                                     ->lockForUpdate() // Kunci baris ini agar tidak ada proses lain
                                     ->firstOrFail();

                // 2. Hanya batalkan jika statusnya 'paid' (Belum Diambil)
                if (strtolower($transaksi->status_pemesanan) != 'paid') {
                    throw new \Exception('Transaksi ini tidak dapat dibatalkan (mungkin sudah selesai atau sudah dibatalkan).');
                }

                // 3. Cari User (Pembeli) dan lock juga
                $user = User::lockForUpdate()->findOrFail($transaksi->user_id);

                // 4. KEMBALIKAN POIN KE USER
                $user->increment('poin_reward', $transaksi->total_harga_poin);

                // 5. UBAH STATUS TRANSAKSI
                $transaksi->status_pemesanan = 'batal';
                $transaksi->save();

                foreach ($transaksi->detailTransaksi as $detail) {
                Produk::where('produk_id', $detail->produk_id)->increment('stok_tersisa', $detail->jumlah);
                }

                return $transaksi; // Kembalikan transaksi yang berhasil
            });

            // Jika transaksi sukses
            return redirect()->route('mitra.riwayat.index')
                             ->with('success', 'Transaksi ' . $result->kode_unik_pengambilan . ' berhasil dibatalkan. Poin telah dikembalikan ke user.');

        } catch (\Exception $e) {
            // Jika terjadi error di dalam transaction
            return redirect()->route('mitra.riwayat.index')
                             ->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
