<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Pastikan ini ada

class MitraVerificationController extends Controller
{

    public function index()
    {
        // Tampilkan mitra yang 'Pending' dulu, lalu sisanya
        $mitra = Mitra::orderByRaw("FIELD(status_verifikasi, 'Pending') DESC")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.mitra.index', compact('mitra'));
    }

    public function show(Mitra $mitra)
    {
        // Tampilkan detail mitra untuk verifikasi
        return view('admin.mitra.show', compact('mitra'));
    }

    // ====================================================================
    // INI ADALAH FUNGSI EDIT YANG BENAR (Hanya menampilkan view)
    // ====================================================================
    public function edit(Mitra $mitra)
    {
        // Fungsi edit HANYA menampilkan form edit.
        return view('admin.mitra.edit', compact('mitra'));
    }

    // ====================================================================
    // INI ADALAH FUNGSI UPDATE YANG BARU (Menyimpan data)
    // ====================================================================
    public function update(Request $request, Mitra $mitra)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'email_bisnis' => 'required|string|email|max:255|unique:mitra,email_bisnis,'.$mitra->mitra_id.',mitra_id',
            'nomor_telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        // 2. Update data utama
        $mitra->update($validated);

        // 3. (SOLUSI PASSWORD) Cek jika admin ingin me-RESET password
        if ($request->filled('password_baru')) {
            $request->validate([
                'password_baru' => 'string|min:8|confirmed',
            ]);
            // Hash dan simpan password baru
            $mitra->password_hash = Hash::make($request->password_baru);
            $mitra->save();
        }

        // Redirect kembali ke index (atau show) dengan pesan sukses
        return redirect()->route('admin.mitra.index')->with('success', 'Data Mitra berhasil diupdate.');
    }

    public function destroy(Mitra $mitra)
    {
        $mitra->delete();
        return redirect()->route('admin.mitra.index')->with('success', 'Mitra berhasil dihapus.');
    }

    // Method untuk menyetujui (Verify)
    public function verify(Mitra $mitra)
    {
        $mitra->status_verifikasi = 'Verified';
        $mitra->save();
        return redirect()->route('admin.mitra.index')->with('success', $mitra->nama_mitra . ' berhasil diverifikasi.');
    }

    // Method untuk menolak (Reject)
    public function reject(Mitra $mitra)
    {
        $mitra->status_verifikasi = 'Rejected';
        $mitra->save();
        return redirect()->route('admin.mitra.index')->with('success', $mitra->nama_mitra . ' berhasil ditolak.');
    }
}
