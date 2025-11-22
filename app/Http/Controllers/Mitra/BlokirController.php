<?php
// app/Http/Controllers/Mitra/BlokirController.php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenyanggahanMitra;
use App\Models\Mitra;

class BlokirController extends Controller
{
    /**
     * Menampilkan Form Sanggahan (Versi Publik/Tanpa Login)
     */
    public function publicIndex()
    {
        return view('mitra.blokir.index');
    }

    /**
     * Memproses Sanggahan (Versi Publik)
     */
    public function publicStore(Request $request)
    {
        $request->validate([
            'email_bisnis' => 'required|email|exists:mitra,email_bisnis', // Cek email harus ada di DB
            'alasan_sanggah' => 'required|string|min:20',
            'bukti_files.*' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_files' => 'required|array|min:1|max:5',
        ]);

        // 1. Cari Mitra Berdasarkan Email
        $mitra = Mitra::where('email_bisnis', $request->email_bisnis)->first();

        // 2. Pastikan akun memang DIBLOKIR (supaya orang iseng tidak nyanggah akun aktif)
        if ($mitra->status_akun !== 'Diblokir') {
            return back()->with('error', 'Akun dengan email ini tidak dalam status Diblokir.');
        }

        // 3. CEK VALIDASI: Apakah sudah ada sanggahan yang STATUSNYA PENDING?
        $pendingAppeal = PenyanggahanMitra::where('mitra_id', $mitra->mitra_id)
                                          ->where('status', 'Pending')
                                          ->first();

        if ($pendingAppeal) {
            return back()->with('error', 'Anda sudah mengirimkan sanggahan sebelumnya dan sedang kami proses. Mohon tunggu keputusan Admin sebelum mengirim lagi.');
        }

        // 4. Proses Upload File
        $filePaths = [];
        if ($request->hasFile('bukti_files')) {
            foreach ($request->file('bukti_files') as $file) {
                $filePaths[] = $file->store('sanggahan', 'public');
            }
        }

        // 5. Simpan ke Database
        PenyanggahanMitra::create([
            'mitra_id' => $mitra->mitra_id,
            'alasan_sanggah' => $request->input('alasan_sanggah'),
            'bukti_files' => $filePaths,
            'status' => 'Pending'
        ]);

        // 6. Redirect Sukses
        return redirect()->route('mitra.login')
                         ->with('success', 'Sanggahan berhasil dikirim. Admin akan meninjau permohonan Anda. Silakan cek email secara berkala.');
    }
}
