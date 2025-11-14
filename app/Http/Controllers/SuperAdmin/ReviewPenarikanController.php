<?php
// app/Http/Controllers/SuperAdmin/ReviewPenarikanController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenarikanDana;
use App\Models\Mitra; // <-- Pastikan import model Mitra
use Illuminate\Support\Facades\DB;

class ReviewPenarikanController extends Controller
{
    /**
     * Menampilkan daftar semua penarikan dana dari Mitra.
     */
    public function index()
    {
        // Ambil semua request penarikan HANYA DARI MITRA
        $penarikanMitra = PenarikanDana::where('penarikanable_type', Mitra::class) // <-- Pastikan namespace 'App\Models\Mitra'
                                      ->with('penarikanable', 'rekeningBank') // Ambil relasi Mitra & Rekening
                                      ->orderBy('created_at', 'desc')
                                      ->get();

        return view('superadmin.review-penarikan.index', compact('penarikanMitra'));
    }

    /**
     * Mengupdate status penarikan (Setuju / Tolak).
     */
    public function update(Request $request, PenarikanDana $penarikan)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        // 1. Pastikan ini penarikan dari Mitra
        if ($penarikan->penarikanable_type != Mitra::class) { // <-- Pastikan namespace 'App\Models\Mitra'
             return redirect()->route('admin.review.penarikan.index')
                             ->with('error', 'Aksi ini hanya untuk penarikan Mitra.');
        }

        // 2. Pastikan statusnya masih Pending
        if ($penarikan->status != 'Pending') {
            return redirect()->route('admin.review.penarikan.index')
                             ->with('error', 'Status penarikan ini sudah diproses.');
        }

        $action = $request->input('action');
        $catatan = $request->input('catatan_admin');

        try {
            DB::transaction(function () use ($penarikan, $action, $catatan) {

                if ($action == 'approve') {
                    // SuperAdmin menyetujui.
                    // (Asumsi: SuperAdmin sudah transfer manual ke rekening Mitra)
                    // Kita hanya update statusnya.
                    $penarikan->update([
                        'status' => 'Selesai',
                        'catatan_admin' => $catatan ?? 'Penarikan disetujui.'
                    ]);

                } elseif ($action == 'reject') {
                    // SuperAdmin menolak.
                    // 1. Kembalikan saldo ke Mitra
                    $mitra = $penarikan->penarikanable; // 'penarikanable' adalah model Mitra
                    $mitra->increment('saldo_pemasukan', $penarikan->jumlah);

                    // 2. Update status penarikan
                    $penarikan->update([
                        'status' => 'Ditolak',
                        'catatan_admin' => $catatan ?? 'Penarikan ditolak.'
                    ]);
                }
            });

            if ($action == 'approve') {
                 return redirect()->route('admin.review.penarikan.index')
                                 ->with('success', 'Penarikan dana berhasil disetujui.');
            } else {
                 return redirect()->route('admin.review.penarikan.index')
                                 ->with('success', 'Penarikan dana berhasil ditolak dan saldo telah dikembalikan ke Mitra.');
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.review.penarikan.index')
                             ->with('error', 'Gagal memproses aksi: ' . $e->getMessage());
        }
    }
}
