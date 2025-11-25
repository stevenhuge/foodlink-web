<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenyanggahanMitra;
use Illuminate\Support\Facades\Mail;
use App\Mail\BalasanSanggahanMail;
use App\Models\Mitra;

class PenyanggahanController extends Controller
{
    public function index()
    {
        // Ambil sanggahan yang masih pending
        $sanggahanList = PenyanggahanMitra::with('mitra')
                                          ->orderBy('created_at', 'desc')
                                          ->get();

        return view('admin.penyanggahan.index', compact('sanggahanList'));
    }

    public function update(Request $request, $id)
    {
        $sanggahan = PenyanggahanMitra::findOrFail($id);
        $action = $request->input('action'); // 'approve' atau 'reject'

        if ($action == 'approve') {
            // 1. Ubah status sanggahan
            $sanggahan->update(['status' => 'Disetujui']);

            // 2. BUKA BLOKIR MITRA
            $mitra = $sanggahan->mitra;
            $mitra->update(['status_akun' => 'Aktif']); // Sesuaikan kolom status Anda

        } else {
            $sanggahan->update(['status' => 'Ditolak']);
            // Mitra tetap diblokir
        }

        return redirect()->back()->with('success', 'Status sanggahan berhasil diperbarui.');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email_tujuan' => 'required|email',
            'email_pengirim' => 'required|email', // Email admin yg ingin ditampilkan
            'subjek' => 'required|string',
            'pesan' => 'required|string',
        ]);

        try {
            Mail::to($request->email_tujuan)
                ->send(new BalasanSanggahanMail(
                    $request->subjek,
                    $request->pesan,
                    $request->email_pengirim
                ));

            return redirect()->back()->with('success', 'Email balasan berhasil dikirim ke Mitra.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
