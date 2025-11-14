<?php
// app/Http/Controllers/Mitra/RekeningBankController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekeningBank;
use Illuminate\Support\Facades\Auth;
use App\Models\Mitra; // <-- Pastikan import model Mitra

class RekeningBankController extends Controller
{
    /**
     * Menampilkan daftar rekening bank milik mitra.
     */
    public function index()
    {
        $mitra = Auth::guard('mitra')->user();
        $rekeningBank = $mitra->rekeningBanks()->orderBy('nama_bank')->get();

        return view('mitra.rekening-bank.index', compact('rekeningBank'));
    }

    /**
     * Menyimpan rekening bank baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik' => 'required|string|max:255',
        ]);

        $mitra = Auth::guard('mitra')->user();

        // Simpan menggunakan relasi polymorphic
        $mitra->rekeningBanks()->create($validatedData);

        return redirect()->route('mitra.rekening-bank.index')
                         ->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit. (Kita akan gunakan modal, tapi fungsi ini untuk 'edit')
     */
    public function edit(RekeningBank $rekeningBank)
    {
        // Keamanan: Pastikan rekening ini milik mitra yang sedang login
        $this->authorizeOwner($rekeningBank);

        return view('mitra.rekening-bank.edit', compact('rekeningBank'));
    }

    /**
     * Update rekening bank.
     */
    public function update(Request $request, RekeningBank $rekeningBank)
    {
        // Keamanan: Pastikan rekening ini milik mitra yang sedang login
        $this->authorizeOwner($rekeningBank);

        $validatedData = $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik' => 'required|string|max:255',
        ]);

        $rekeningBank->update($validatedData);

        return redirect()->route('mitra.rekening-bank.index')
                         ->with('success', 'Rekening bank berhasil diperbarui.');
    }

    /**
     * Menghapus rekening bank.
     */
    public function destroy(RekeningBank $rekeningBank)
    {
        // Keamanan: Pastikan rekening ini milik mitra yang sedang login
        $this->authorizeOwner($rekeningBank);

        // Cek apakah rekening sedang dipakai di penarikan PENDING
        $isUsedInPending = $rekeningBank->penarikanDana()
                                       ->where('status', 'Pending')
                                       ->exists();

        if ($isUsedInPending) {
            return redirect()->route('mitra.rekening-bank.index')
                             ->with('error', 'Tidak dapat menghapus rekening. Masih ada penarikan dana "Pending" yang menggunakan rekening ini.');
        }

        $rekeningBank->delete();

        return redirect()->route('mitra.rekening-bank.index')
                         ->with('success', 'Rekening bank berhasil dihapus.');
    }

    /**
     * Fungsi helper untuk keamanan
     */
    private function authorizeOwner(RekeningBank $rekeningBank)
    {
        if ($rekeningBank->rekeningable_id != Auth::guard('mitra')->id() ||
            $rekeningBank->rekeningable_type != Mitra::class) { // <-- Pastikan namespace 'App\Models\Mitra'
            abort(403, 'Akses Ditolak.');
        }
    }
}
