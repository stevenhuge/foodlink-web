<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use App\Models\KategoriUsaha;
use App\Models\AlasanBlokirOption; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MitraVerificationController extends Controller
{
    public function index()
    {
        $mitra = Mitra::with(['kategoriUsaha', 'alasanBlokir']) // Eager load relasi
            ->orderByRaw("FIELD(status_verifikasi, 'Pending') DESC")
            ->orderBy('created_at', 'desc')
            ->get();
        // Ambil juga pilihan alasan untuk dropdown di view
        $alasanBlokirOptions = AlasanBlokirOption::orderBy('alasan_text')->get();

        return view('admin.mitra.index', compact('mitra', 'alasanBlokirOptions')); // Kirim $alasanBlokirOptions
    }

    public function show(Mitra $mitra){ return view('admin.mitra.show', compact('mitra')); }
    public function edit(Mitra $mitra){ $kategoriUsaha = KategoriUsaha::orderBy('nama_kategori')->get(); return view('admin.mitra.edit', compact('mitra', 'kategoriUsaha')); }
    public function update(Request $request, Mitra $mitra){ /* ... kode update ... */ return redirect()->route('admin.mitra.index')->with('success', 'Data Mitra berhasil diupdate.'); }
    public function destroy(Mitra $mitra){ /* ... kode destroy ... */ }
    public function verify(Mitra $mitra){ $mitra->status_verifikasi = 'Verified'; $mitra->save(); return redirect()->route('admin.mitra.index')->with('success', $mitra->nama_mitra . ' berhasil diverifikasi.'); }
    public function reject(Mitra $mitra){ $mitra->status_verifikasi = 'Rejected'; $mitra->save(); return redirect()->route('admin.mitra.index')->with('success', $mitra->nama_mitra . ' berhasil ditolak.'); }

    /**
     * Blokir akun Mitra dengan memilih alasan.
     */
    public function block(Request $request, Mitra $mitra)
    {
        $request->validate(['alasan_blokir_option_id' => 'required|integer|exists:alasan_blokir_options,alasan_id'], [ /* ... pesan error ... */ ]);
        $mitra->status_akun = 'Diblokir';
        $mitra->alasan_blokir_option_id = $request->input('alasan_blokir_option_id'); // Simpan ID alasan
        $mitra->save();
        return redirect()->route('admin.mitra.index')->with('success', 'Akun ' . $mitra->nama_mitra . ' berhasil diblokir.');
    }

    /**
     * Aktifkan kembali akun Mitra.
     */
    public function unblock(Mitra $mitra)
    {
        $mitra->status_akun = 'Aktif';
        $mitra->alasan_blokir_option_id = null; // Hapus ID alasan
        $mitra->save();
        return redirect()->route('admin.mitra.index')->with('success', 'Akun ' . $mitra->nama_mitra . ' berhasil diaktifkan kembali.');
    }
}
