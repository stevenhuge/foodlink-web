<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlasanBlokirOption;
use Illuminate\Http\Request;

class AlasanBlokirController extends Controller
{
    /**
     * Menampilkan daftar alasan blokir.
     */
    public function index()
    {
        $alasanList = AlasanBlokirOption::orderBy('alasan_text')->get();
        return view('admin.alasan-blokir.index', compact('alasanList'));
    }

    /**
     * Menampilkan form tambah alasan.
     */
    public function create()
    {
        return view('admin.alasan-blokir.create');
    }

    /**
     * Menyimpan alasan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alasan_text' => 'required|string|max:255|unique:alasan_blokir_options,alasan_text',
        ],[
            'alasan_text.required' => 'Teks alasan wajib diisi.',
            'alasan_text.unique' => 'Teks alasan ini sudah ada.',
        ]);

        AlasanBlokirOption::create($validated);

        return redirect()->route('admin.alasan-blokir.index')->with('success', 'Alasan blokir baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit alasan.
     * * PERBAIKAN: Nama variabel diubah dari $alasanBlokirOption menjadi $alasan_blokir
     * agar cocok dengan parameter route model binding (yang default-nya snake_case).
     */
    public function edit(AlasanBlokirOption $alasan_blokir)
    {
        // PERBAIKAN: Kirim variabel dengan nama $alasan_blokir ke view
        return view('admin.alasan-blokir.edit', compact('alasan_blokir'));
    }

    /**
     * Mengupdate alasan yang ada.
     * * PERBAIKAN: Nama variabel diubah menjadi $alasan_blokir
     */
    public function update(Request $request, AlasanBlokirOption $alasan_blokir)
    {
        $validated = $request->validate([
            // PERBAIKAN: Gunakan ID dari $alasan_blokir
            'alasan_text' => 'required|string|max:255|unique:alasan_blokir_options,alasan_text,' . $alasan_blokir->alasan_id . ',alasan_id',
        ],[
            'alasan_text.required' => 'Teks alasan wajib diisi.',
            'alasan_text.unique' => 'Teks alasan ini sudah ada.',
        ]);

        // PERBAIKAN: Update model $alasan_blokir
        $alasan_blokir->update($validated);

        return redirect()->route('admin.alasan-blokir.index')->with('success', 'Alasan blokir berhasil diperbarui.');
    }

    /**
     * Menghapus alasan.
     * * PERBAIKAN: Nama variabel diubah menjadi $alasan_blokir
     */
    public function destroy(AlasanBlokirOption $alasan_blokir)
    {
         try {
            // Relasi di tabel mitra di set 'set null', jadi bisa dihapus
            // PERBAIKAN: Hapus model $alasan_blokir
            $alasan_blokir->delete();
            return redirect()->route('admin.alasan-blokir.index')->with('success', 'Alasan blokir berhasil dihapus.');
         } catch (\Illuminate\Database\QueryException $e) {
             return redirect()->route('admin.alasan-blokir.index')->with('error', 'Gagal menghapus alasan: ' . $e->getMessage());
         }
    }
}
