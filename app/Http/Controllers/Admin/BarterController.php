<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barter;
use Illuminate\Http\Request;

class BarterController extends Controller
{
    /**
     * Menampilkan daftar semua riwayat barter untuk keperluan Audit Admin.
     */
    public function index(Request $request)
    {
        // Query builder untuk filter pencarian (opsional)
        $query = Barter::with(['pengajuMitra', 'penerimaMitra', 'pengajuUser', 'produkDiminta', 'produkDitawarkan'])
                       ->orderBy('waktu_pengajuan', 'desc');

        // Fitur pencarian sederhana berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_barter', $request->status);
        }

        // Pagination
        $barters = $query->paginate(15);

        return view('admin.barter.index', compact('barters'));
    }

    /**
     * Menampilkan detail lengkap dari satu transaksi barter.
     */
    public function show($id)
    {
        // Menggunakan withTrashed jika model implement soft delete (tapi karena Barter Anda bukan SoftDelete, cukup findOrFail)
        $barter = Barter::with(['pengajuMitra', 'penerimaMitra', 'pengajuUser', 'produkDiminta', 'produkDitawarkan'])
                        ->findOrFail($id);

        return view('admin.barter.show', compact('barter'));
    }
}
