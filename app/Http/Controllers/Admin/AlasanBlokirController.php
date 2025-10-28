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
     * $alasanBlokirOption akan otomatis di-resolve oleh Laravel (Route Model Binding)
     */
    public function edit(AlasanBlokirOption $alasanBlokirOption)
    {
        return view('admin.alasan-blokir.edit', compact('alasanBlokirOption'));
    }

    /**
     * Mengupdate alasan yang ada.
     */
    public function update(Request $request, AlasanBlokirOption $alasanBlokirOption)
    {
        $validated = $request->validate([
            'alasan_text' => 'required|string|max:255|unique:alasan_blokir_options,alasan_text,' . $alasanBlokirOption->alasan_id . ',alasan_id',
        ],[
            'alasan_text.required' => 'Teks alasan wajib diisi.',
            'alasan_text.unique' => 'Teks alasan ini sudah ada.',
        ]);

        $alasanBlokirOption->update($validated);

        return redirect()->route('admin.alasan-blokir.index')->with('success', 'Alasan blokir berhasil diperbarui.');
    }

    /**
     * Menghapus alasan.
     */
    public function destroy(AlasanBlokirOption $alasanBlokirOption)
    {
         try {
            // Relasi di tabel mitra di set 'set null', jadi bisa dihapus
            $alasanBlokirOption->delete();
            return redirect()->route('admin.alasan-blokir.index')->with('success', 'Alasan blokir berhasil dihapus.');
         } catch (\Illuminate\Database\QueryException $e) {
             return redirect()->route('admin.alasan-blokir.index')->with('error', 'Gagal menghapus alasan: ' . $e->getMessage());
         }
    }
}
