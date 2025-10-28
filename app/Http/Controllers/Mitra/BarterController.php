<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Barter;
use App\Models\KategoriProduk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BarterController extends Controller
{
    // ... (fungsi index() tidak berubah) ...
    public function index()
    {
        $mitraId = Auth::guard('mitra')->id();
        $produks = Produk::where('mitra_id', '!=', $mitraId)
                        ->where('status_produk', 'Tersedia')
                        ->where('tipe_penawaran', '!=', 'Donasi')
                        ->with('mitra')
                        ->orderBy('created_at', 'desc')
                        ->get();
        $pendingOffers = Barter::where('pengaju_mitra_id', $mitraId)
                            ->where('status_barter', 'Diajukan')
                            ->where('waktu_pengajuan', '>', now()->subHours(12))
                            ->pluck('produk_diminta_id');
        return view('mitra.barter.index', compact('produks', 'pendingOffers'));
    }


    /**
     * Tampilkan form untuk mengajukan barter.
     * (Modifikasi: Kirim data stok produk pribadi)
     */
    public function create(Produk $produk)
    {
        $mitraId = Auth::guard('mitra')->id();
        if ($produk->mitra_id === $mitraId) {
            return redirect()->route('mitra.barter.index')->with('error', 'Anda tidak bisa barter dengan diri sendiri.');
        }

        // Ambil produk PRIBADI yang tersedia (beserta stoknya)
        $produkPribadi = Produk::where('mitra_id', $mitraId)
                               ->where('status_produk', 'Tersedia')
                               ->select('produk_id', 'nama_produk', 'stok_tersisa') // Ambil kolom yg perlu saja
                               ->get();

        $kategoris = KategoriProduk::all();

        return view('mitra.barter.create', compact('produk', 'produkPribadi', 'kategoris'));
    }

    /**
     * Simpan penawaran barter ke database.
     * (Modifikasi: Validasi & Simpan Kuantitas)
     */
    public function store(Request $request, Produk $produk)
    {
        $mitraId = Auth::guard('mitra')->id();

        $request->validate([
            'tipe_tawaran' => ['required', Rule::in(['existing', 'manual'])],
        ]);

        $dataToSave = [
            'tipe_barter' => 'Mitra-Mitra',
            'pengaju_mitra_id' => $mitraId,
            'penerima_mitra_id' => $produk->mitra_id,
            'produk_diminta_id' => $produk->produk_id,
            'status_barter' => 'Diajukan',
            'produk_ditawarkan_id' => null,
            'jumlah_ditawarkan' => null, // <-- Set default null
            'nama_barang_manual' => null,
            'deskripsi_barang_manual' => null,
            'foto_barang_manual' => null,
            'bukti_struk' => null,
        ];

        // --- Logika Opsi 1 (Pilih Produk Sendiri) ---
        if ($request->input('tipe_tawaran') === 'existing') {
            // Validasi produk ID dan kuantitas
            $validated = $request->validate([
                'produk_ditawarkan_id' => [
                    'required',
                    Rule::exists('produk', 'produk_id')->where(function ($query) use ($mitraId) {
                        return $query->where('mitra_id', $mitraId)->where('status_produk', 'Tersedia'); // Pastikan produknya tersedia
                    })
                ],
                // Validasi Kuantitas
                'jumlah_ditawarkan' => [
                    'required',
                    'integer',
                    'min:1',
                    // Pastikan jumlah tidak melebihi stok produk yang dipilih
                    function ($attribute, $value, $fail) use ($request, $mitraId) {
                        $selectedProductId = $request->input('produk_ditawarkan_id');
                        if ($selectedProductId) {
                            $product = Produk::where('produk_id', $selectedProductId)
                                             ->where('mitra_id', $mitraId) // Milik sendiri
                                             ->first();
                            if ($product && $value > $product->stok_tersisa) {
                                $fail("Jumlah yang ditawarkan ({$value}) melebihi stok tersedia ({$product->stok_tersisa}).");
                            }
                        } else {
                             $fail("Pilih produk terlebih dahulu."); // Jika produk belum dipilih
                        }
                    },
                ]
            ]);
            $dataToSave['produk_ditawarkan_id'] = $validated['produk_ditawarkan_id'];
            $dataToSave['jumlah_ditawarkan'] = $validated['jumlah_ditawarkan']; // Simpan kuantitas
        }

        // --- Logika Opsi 2 (Input Manual) ---
        else {
             // ... (Validasi dan logika Opsi 2 tidak berubah) ...
             $validated = $request->validate([
                'nama_barang_manual' => 'required|string|max:255',
                'deskripsi_barang_manual' => 'required|string|max:1000',
                'foto_barang_manual' => 'required|image|mimes:jpeg,png,jpg|max:1024',
                'bukti_struk' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:1024',
                'kategori_id' => 'required|exists:kategori_produk,kategori_id',
                'harga_perkiraan' => 'required|numeric|min:0',
             ]);
            $fotoPath = $request->file('foto_barang_manual')->store('barter/manual_foto', 'public');
            $dataToSave['foto_barang_manual'] = $fotoPath;
            if ($request->hasFile('bukti_struk')) {
                $strukPath = $request->file('bukti_struk')->store('barter/struk', 'public');
                $dataToSave['bukti_struk'] = $strukPath;
            }
            $dataToSave['nama_barang_manual'] = $validated['nama_barang_manual'];
            $dataToSave['deskripsi_barang_manual'] = json_encode([
                'deskripsi' => $validated['deskripsi_barang_manual'],
                'kategori_id' => $validated['kategori_id'],
                'harga_perkiraan' => $validated['harga_perkiraan']
            ]);
        }

        // Simpan ke database
        Barter::create($dataToSave);

        return redirect()->route('mitra.barter.inbox')->with('success', 'Penawaran barter berhasil diajukan!');
    }

    // ... (fungsi inbox(), reject(), cancel() tidak berubah) ...
    public function inbox()
    {
        $mitraId = Auth::guard('mitra')->id();
        $tawaranTerkirim = Barter::where('pengaju_mitra_id', $mitraId)
                            ->with('penerimaMitra', 'produkDiminta', 'produkDitawarkan')
                            ->orderBy('waktu_pengajuan', 'desc')
                            ->get();
        $tawaranDiterima = Barter::where('penerima_mitra_id', $mitraId)
                            ->with('pengajuMitra', 'produkDiminta', 'produkDitawarkan')
                            ->orderBy('waktu_pengajuan', 'desc')
                            ->get();
        return view('mitra.barter.inbox', compact('tawaranTerkirim', 'tawaranDiterima'));
    }
    public function reject(Barter $barter)
    {
        if ($barter->penerima_mitra_id !== Auth::guard('mitra')->id()) { abort(403); }
        if ($barter->status_barter !== 'Diajukan') { return redirect()->route('mitra.barter.inbox')->with('error', 'Tawaran ini sudah tidak valid.'); }
        $barter->status_barter = 'Ditolak';
        $barter->save();
        return redirect()->route('mitra.barter.inbox')->with('success', 'Barter berhasil ditolak.');
    }
    public function cancel(Barter $barter)
    {
        if ($barter->pengaju_mitra_id !== Auth::guard('mitra')->id()) { abort(403); }
        if ($barter->status_barter !== 'Diajukan') { return redirect()->route('mitra.barter.inbox')->with('error', 'Tawaran ini tidak bisa dibatalkan.'); }
        $barter->status_barter = 'Dibatalkan';
        $barter->save();
        return redirect()->route('mitra.barter.inbox')->with('success', 'Tawaran barter dibatalkan.');
    }

    /**
     * Aksi: Terima tawaran barter
     * (Modifikasi: Kurangi stok produk ditawarkan)
     */
    // app/Http/Controllers/Mitra/BarterController.php

    /**
     * Aksi: Terima tawaran barter
     * (Modifikasi: Kurangi stok LAMA, Buat produk BARU untuk penerima)
     */
    public function accept(Barter $barter)
    {
        // 1. Otorisasi (Sama seperti sebelumnya)
        if ($barter->penerima_mitra_id !== Auth::guard('mitra')->id()) { abort(403); }
        if ($barter->status_barter !== 'Diajukan') { return redirect()->route('mitra.barter.inbox')->with('error', 'Tawaran ini sudah tidak valid.'); }

        // --- Logika Inti ---

        // A. Produk yang diminta (Dimiliki Mitra B [Penerima], Pindah ke Mitra A [Pengaju]) - Tidak Berubah
        $produkDiminta = $barter->produkDiminta;
        if ($produkDiminta) {
            $produkDiminta->mitra_id = $barter->pengaju_mitra_id; // Pindah ke Pengaju (A)
            $produkDiminta->status_produk = 'Ditarik'; // Jadi Draft
            $produkDiminta->save();
        }

        // B. Produk yang ditawarkan (Dimiliki Mitra A [Pengaju], Sebagian Pindah ke Mitra B [Penerima])

        // Opsi 1: Jika pengaju menawarkan produknya yang sudah ada
        if ($barter->produk_ditawarkan_id) {
            $produkDitawarkanAsli = $barter->produkDitawarkan; // Produk asli milik Mitra A
            if ($produkDitawarkanAsli) {
                // 1. Kurangi stok produk ASLI milik Mitra A
                $stokLama = $produkDitawarkanAsli->stok_tersisa;
                $jumlahDitransfer = $barter->jumlah_ditawarkan;
                $stokBaru = $stokLama - $jumlahDitransfer;

                $produkDitawarkanAsli->stok_tersisa = max(0, $stokBaru); // Pastikan tidak negatif
                if ($produkDitawarkanAsli->stok_tersisa <= 0) {
                    $produkDitawarkanAsli->status_produk = 'Habis';
                }
                $produkDitawarkanAsli->save();

                // 2. BUAT Produk BARU untuk Mitra B (Penerima)
                // Salin data dari produk asli
                Produk::create([
                    'mitra_id' => $barter->penerima_mitra_id, // Milik Penerima (B)
                    'kategori_id' => $produkDitawarkanAsli->kategori_id,
                    'nama_produk' => $produkDitawarkanAsli->nama_produk, // Salin nama
                    'deskripsi' => $produkDitawarkanAsli->deskripsi, // Salin deskripsi
                    'foto_produk' => $produkDitawarkanAsli->foto_produk, // Salin path foto
                    'harga_normal' => $produkDitawarkanAsli->harga_normal, // Salin harga
                    'harga_diskon' => $produkDitawarkanAsli->harga_diskon, // Salin harga
                    'tipe_penawaran' => $produkDitawarkanAsli->tipe_penawaran, // Salin tipe
                    'stok_awal' => $jumlahDitransfer, // Stok awal = jumlah yg ditransfer
                    'stok_tersisa' => $jumlahDitransfer, // Stok tersisa = jumlah yg ditransfer
                    'waktu_kadaluarsa' => $produkDitawarkanAsli->waktu_kadaluarsa, // Salin kadaluarsa
                    'waktu_ambil_mulai' => now(), // Waktu ambil bisa diset ulang
                    'waktu_ambil_selesai' => now()->addDays(7), // Default 1 minggu dari sekarang
                    'status_produk' => 'Ditarik', // Masuk sebagai Draft
                ]);
            }
        }

        // Opsi 2: Jika pengaju menawarkan barang manual (Logika Tidak Berubah)
        else {
            $dataManual = json_decode($barter->deskripsi_barang_manual, true);
            Produk::create([
                'mitra_id' => $barter->penerima_mitra_id,
                'kategori_id' => $dataManual['kategori_id'] ?? KategoriProduk::where('nama_kategori', 'Lainnya')->first()->kategori_id ?? 1,
                'nama_produk' => $barter->nama_barang_manual,
                'deskripsi' => $dataManual['deskripsi'] ?? '',
                'foto_produk' => $barter->foto_barang_manual,
                'harga_normal' => $dataManual['harga_perkiraan'] ?? 0,
                'harga_diskon' => $dataManual['harga_perkiraan'] ?? 0,
                'tipe_penawaran' => 'Jual-Cepat',
                'stok_awal' => 1,
                'stok_tersisa' => 1,
                'waktu_kadaluarsa' => now()->addDays(7),
                'waktu_ambil_mulai' => now(),
                'waktu_ambil_selesai' => now()->addDays(7),
                'status_produk' => 'Ditarik',
            ]);
        }

        // 5. Set status barter (Sama seperti sebelumnya)
        $barter->status_barter = 'Selesai';
        $barter->save();

        return redirect()->route('mitra.barter.inbox')->with('success', 'Barter berhasil diterima! Produk telah ditransfer/dibuat dan ditambahkan ke daftar produk Anda sebagai draft.');
    }

    // ... (Fungsi reject() dan cancel() tidak perlu diubah) ...
}
