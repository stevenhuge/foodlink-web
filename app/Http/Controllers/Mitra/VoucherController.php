<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\MitraVoucher;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = MitraVoucher::where('mitra_id', Auth::guard('mitra')->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('mitra.voucher.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produks = Produk::where('mitra_id', Auth::guard('mitra')->id())->get();
        return view('mitra.voucher.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_voucher' => 'required|string|max:255',
            'kode_voucher' => 'required|string|max:50|unique:mitra_vouchers,kode_voucher',
            'tipe_potongan' => 'required|in:persentase,nominal',
            'nilai_potongan' => 'required|numeric|min:0',
            'alokasi' => 'required|in:semua_menu,menu_tertentu',
            'produk_id' => 'nullable|required_if:alokasi,menu_tertentu|exists:produk,produk_id',
            'minimal_belanja' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->all();
        $data['mitra_id'] = Auth::guard('mitra')->id();
        
        if ($data['alokasi'] === 'semua_menu') {
            $data['produk_id'] = null;
        }
        
        // Validate percentage max
        if ($data['tipe_potongan'] === 'persentase' && $data['nilai_potongan'] > 100) {
            return back()->withErrors(['nilai_potongan' => 'Nilai potongan persentase tidak boleh lebih dari 100%.'])->withInput();
        }

        MitraVoucher::create($data);

        return redirect()->route('mitra.voucher.index')->with('success', 'Voucher berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucher = MitraVoucher::where('mitra_id', Auth::guard('mitra')->id())->findOrFail($id);
        $produks = Produk::where('mitra_id', Auth::guard('mitra')->id())->get();
        
        return view('mitra.voucher.edit', compact('voucher', 'produks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $voucher = MitraVoucher::where('mitra_id', Auth::guard('mitra')->id())->findOrFail($id);
        
        $request->validate([
            'nama_voucher' => 'required|string|max:255',
            'kode_voucher' => 'required|string|max:50|unique:mitra_vouchers,kode_voucher,' . $voucher->id,
            'tipe_potongan' => 'required|in:persentase,nominal',
            'nilai_potongan' => 'required|numeric|min:0',
            'alokasi' => 'required|in:semua_menu,menu_tertentu',
            'produk_id' => 'nullable|required_if:alokasi,menu_tertentu|exists:produk,produk_id',
            'minimal_belanja' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $data = $request->all();
        
        if ($data['alokasi'] === 'semua_menu') {
            $data['produk_id'] = null;
        }

        if ($data['tipe_potongan'] === 'persentase' && $data['nilai_potongan'] > 100) {
            return back()->withErrors(['nilai_potongan' => 'Nilai potongan persentase tidak boleh lebih dari 100%.'])->withInput();
        }

        $voucher->update($data);

        return redirect()->route('mitra.voucher.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $voucher = MitraVoucher::where('mitra_id', Auth::guard('mitra')->id())->findOrFail($id);
        $voucher->delete();

        return redirect()->route('mitra.voucher.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
