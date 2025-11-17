<?php
// app/Http/Controllers/SuperAdmin/RekeningBankController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RekeningBank;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // <-- Pastikan import model Admin

class RekeningBankController extends Controller
{
    /**
     * Menampilkan daftar rekening bank milik SuperAdmin.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $rekeningBank = $admin->rekeningBanks()->orderBy('nama_bank')->get();

        return view('superadmin.rekening-bank.index', compact('rekeningBank'));
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

        $admin = Auth::guard('admin')->user();

        // Simpan menggunakan relasi polymorphic
        $admin->rekeningBanks()->create($validatedData);

        return redirect()->route('admin.rekening-bank.index')
                         ->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit. (Kita akan gunakan modal)
     */
    public function edit(RekeningBank $rekeningBank)
    {
        // Keamanan: Pastikan rekening ini milik SuperAdmin yang sedang login
        $this->authorizeOwner($rekeningBank);

        // Fungsionalitas edit akan kita taruh di modal pada halaman index
        return redirect()->route('admin.rekening-bank.index');
    }

    /**
     * Update rekening bank.
     */
    public function update(Request $request, RekeningBank $rekeningBank)
    {
        // Keamanan: Pastikan rekening ini milik SuperAdmin yang sedang login
        $this->authorizeOwner($rekeningBank);

        $validatedData = $request->validate([
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik' => 'required|string|max:255',
        ]);

        $rekeningBank->update($validatedData);

        return redirect()->route('admin.rekening-bank.index')
                         ->with('success', 'Rekening bank berhasil diperbarui.');
    }

    /**
     * Menghapus rekening bank.
     */
    public function destroy(RekeningBank $rekeningBank)
    {
        // Keamanan: Pastikan rekening ini milik SuperAdmin yang sedang login
        $this->authorizeOwner($rekeningBank);

        // Cek apakah rekening sedang dipakai di penarikan PENDING
        // (Untuk SuperAdmin mungkin statusnya 'Selesai', tapi cek 'Pending' untuk Mitra)
        $isUsedInPending = $rekeningBank->penarikanDana()
                                       ->whereIn('status', ['Pending', 'Diproses'])
                                       ->exists();

        if ($isUsedInPending) {
            return redirect()->route('admin.rekening-bank.index')
                             ->with('error', 'Tidak dapat menghapus rekening. Masih ada penarikan dana "Pending" yang menggunakan rekening ini.');
        }

        $rekeningBank->delete();

        return redirect()->route('admin.rekening-bank.index')
                         ->with('success', 'Rekening bank berhasil dihapus.');
    }

    /**
     * Fungsi helper untuk keamanan
     */
    private function authorizeOwner(RekeningBank $rekeningBank)
    {
        if ($rekeningBank->rekeningable_id != Auth::guard('admin')->id() ||
            $rekeningBank->rekeningable_type != Admin::class) { // <-- Pastikan namespace 'App\Models\Admin'
            abort(403, 'Akses Ditolak.');
        }
    }
}
