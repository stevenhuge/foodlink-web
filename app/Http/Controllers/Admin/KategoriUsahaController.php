<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUsaha;
use Illuminate\Http\Request;

class KategoriUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = KategoriUsaha::orderBy('nama_kategori')->get();
        return view('admin.kategori-usaha.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori-usaha.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Validasi: nama wajib diisi, unik di tabel kategori_usaha
            'nama_kategori' => 'required|string|max:255|unique:kategori_usaha,nama_kategori',
        ]);
        KategoriUsaha::create($validated);
        return redirect()->route('admin.kategori-usaha.index')->with('success', 'Kategori Usaha baru berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriUsaha $kategoriUsaha) // Gunakan route model binding
    {
        // Tampilkan view edit dan kirim data kategori yang dipilih
        return view('admin.kategori-usaha.edit', compact('kategoriUsaha'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriUsaha $kategoriUsaha) // Gunakan route model binding
    {
        $validated = $request->validate([
            // Validasi: nama wajib diisi, unik KECUALI untuk ID kategori ini sendiri
            'nama_kategori' => 'required|string|max:255|unique:kategori_usaha,nama_kategori,' . $kategoriUsaha->kategori_usaha_id . ',kategori_usaha_id',
        ]);

        $kategoriUsaha->update($validated); // Update data

        return redirect()->route('admin.kategori-usaha.index')->with('success', 'Kategori Usaha berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriUsaha $kategoriUsaha) // Gunakan route model binding
    {
        try {
            $kategoriUsaha->delete(); // Hapus data
            return redirect()->route('admin.kategori-usaha.index')->with('success', 'Kategori Usaha berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap error jika kategori masih digunakan oleh Mitra (foreign key constraint)
            if ($e->getCode() === '23000') { // Kode error integrity constraint violation
                return redirect()->route('admin.kategori-usaha.index')->with('error', 'Kategori Usaha tidak bisa dihapus karena masih digunakan oleh beberapa Mitra.');
            }
            // Jika error lain, tampilkan pesan error umum
            return redirect()->route('admin.kategori-usaha.index')->with('error', 'Gagal menghapus Kategori Usaha: ' . $e->getMessage());
        }
    }
}
