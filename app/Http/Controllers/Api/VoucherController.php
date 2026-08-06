<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MitraVoucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VoucherController extends Controller
{
    /**
     * Mengambil daftar voucher aktif untuk pembeli
     */
    public function index(Request $request)
    {
        $now = Carbon::now();

        $query = MitraVoucher::with([
            'mitra:mitra_id,nama_mitra,logo_mitra',
            'produk:produk_id,nama_produk'
        ])
        ->where('status', 'aktif')
        ->where('kuota', '>', 0)
        ->where('tanggal_mulai', '<=', $now)
        ->where('tanggal_selesai', '>=', $now);

        // Filter berdasarkan ID Mitra / Toko jika dikirimkan oleh Android App
        if ($request->has('mitra_id') && !empty($request->mitra_id)) {
            $query->where('mitra_id', $request->mitra_id);
        }

        $vouchers = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar voucher berhasil diambil',
            'data'    => $vouchers
        ], 200);
    }

    /**
     * Mengecek & memvalidasi kode voucher saat checkout di Android App
     */
    public function cekVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher'  => 'required|string',
            'mitra_id'      => 'required|integer',
            'total_belanja' => 'nullable|numeric|min:0',
        ]);

        $now = Carbon::now();

        $voucher = MitraVoucher::where('kode_voucher', $request->kode_voucher)
            ->where('mitra_id', $request->mitra_id)
            ->where('status', 'aktif')
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher tidak valid atau tidak tersedia untuk toko ini.'
            ], 404);
        }

        if ($voucher->kuota <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kuota voucher telah habis.'
            ], 400);
        }

        if ($voucher->tanggal_mulai > $now || $voucher->tanggal_selesai < $now) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kadaluwarsa atau belum berlaku.'
            ], 400);
        }

        if ($request->filled('total_belanja') && $request->total_belanja < $voucher->minimal_belanja) {
            return response()->json([
                'success' => false,
                'message' => 'Total belanja belum mencapai minimum transaksi (Rp ' . number_format($voucher->minimal_belanja, 0, ',', '.') . ').'
            ], 400);
        }

        // Hitung perkiraan nilai potongan diskon
        $potongan = 0;
        if ($request->filled('total_belanja')) {
            if ($voucher->tipe_potongan === 'persentase') {
                $potongan = ($request->total_belanja * $voucher->nilai_potongan) / 100;
            } else {
                $potongan = $voucher->nilai_potongan;
            }

            if ($potongan > $request->total_belanja) {
                $potongan = $request->total_belanja;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Voucher valid dan dapat digunakan',
            'data'    => array_merge($voucher->toArray(), [
                'nominal_potongan_dihitung' => $potongan
            ])
        ], 200);
    }
}
