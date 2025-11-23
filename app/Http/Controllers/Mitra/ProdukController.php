<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Helper function untuk otorisasi sederhana.
     */
    private function checkOwnership(Produk $produk)
    {
        if ($produk->mitra_id !== Auth::guard('mitra')->id()) {
            abort(403, 'Anda tidak diizinkan melakukan aksi ini.');
        }
    }

    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        $mitraId = Auth::guard('mitra')->id();
        $produks = Produk::where('mitra_id', $mitraId)
                        ->with('kategori')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('mitra.produk.index', compact('produks'));
    }

    /**
     * Tampilkan form create.
     */
    public function create()
    {
        $kategoris = KategoriProduk::all();
        return view('mitra.produk.create', compact('kategoris'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_produk,kategori_id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_penawaran' => 'required|in:Jual-Cepat,Donasi',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_normal',
            'stok_awal' => 'required|integer|min:1',
            'waktu_kadaluarsa' => 'required|date',
            'waktu_ambil_mulai' => 'required|date|after_or_equal:now',
            'waktu_ambil_selesai' => 'required|date|after:waktu_ambil_mulai',

            // --- PERUBAHAN DI SINI ---
            // Mengubah max:2048 (2MB) menjadi max:1024 (1MB)
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2024',
        ]);

        $mitraId = Auth::guard('mitra')->id();
        $dataToSave = array_merge($validated, [
            'mitra_id' => $mitraId,
            'stok_tersisa' => $validated['stok_awal'],
            'status_produk' => 'Ditarik',
            'harga_normal' => ($validated['tipe_penawaran'] === 'Donasi') ? 0 : $validated['harga_normal'],
            'harga_diskon' => ($validated['tipe_penawaran'] === 'Donasi') ? 0 : $validated['harga_diskon'],
        ]);

        if ($request->hasFile('foto_produk')) {
            $path = $request->file('foto_produk')->store('produk', 'public');
            $dataToSave['foto_produk'] = $path;
        }

        Produk::create($dataToSave);

        return redirect()->route('mitra.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit.
     */
    public function edit(Produk $produk)
    {
        $this->checkOwnership($produk); // Otorisasi
        $kategoris = KategoriProduk::all();
        return view('mitra.produk.edit', compact('produk', 'kategoris'));
    }

    /**
     * Update produk di database.
     */
    public function update(Request $request, Produk $produk)
    {
        $this->checkOwnership($produk); // Otorisasi

        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_produk,kategori_id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe_penawaran' => 'required|in:Jual-Cepat,Donasi',
            'harga_normal' => 'required|numeric|min:0',
            'harga_diskon' => 'required|numeric|min:0|lte:harga_normal',
            'stok_awal' => 'required|integer|min:0',
            'stok_tersisa' => 'required|integer|min:0|lte:stok_awal',
            'waktu_kadaluarsa' => 'required|date',
            'waktu_ambil_mulai' => 'required|date',
            'waktu_ambil_selesai' => 'required|date|after:waktu_ambil_mulai',
            'status_produk' => 'required|in:Tersedia,Habis,Ditarik',

            // --- PERUBAHAN DI SINI ---
            // Mengubah max:2048 (2MB) menjadi max:1024 (1MB)
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $dataToUpdate = $validated;

        if ($validated['tipe_penawaran'] === 'Donasi') {
            $dataToUpdate['harga_normal'] = 0;
            $dataToUpdate['harga_diskon'] = 0;
        }

        // Logika Update Foto
        if ($request->hasFile('foto_produk')) {
            if ($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            $path = $request->file('foto_produk')->store('produk', 'public');
            $dataToUpdate['foto_produk'] = $path;
        }

        $produk->update($dataToUpdate);

        return redirect()->route('mitra.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Hapus produk dari database.
     */
    public function destroy(Produk $produk)
    {
        $this->checkOwnership($produk); // Otorisasi

        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->route('mitra.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function publish(Produk $produk)
    {
        // Pastikan produk ini milik mitra yang sedang login
        $this->checkOwnership($produk);

        $produk->status_produk = 'Tersedia';
        $produk->save();

        return redirect()->route('mitra.produk.index')->with('success', 'Produk berhasil dipublikasikan.');
    }

    public function unpublish(Produk $produk)
    {
        // Pastikan produk ini milik mitra yang sedang login
        $this->checkOwnership($produk);

        $produk->status_produk = 'Ditarik';
        $produk->save();

        return redirect()->route('mitra.produk.index')->with('success', 'Produk berhasil ditarik (draft).');
    }
}
