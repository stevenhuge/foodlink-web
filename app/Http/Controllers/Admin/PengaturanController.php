<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Mitra;
use App\Models\MitraNotifikasi;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('admin.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'keterangan_perubahan' => 'required|string|min:10',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        $mitras = Mitra::all();

        foreach ($mitras as $mitra) {
            MitraNotifikasi::create([
                'mitra_id' => $mitra->mitra_id,
                'judul' => 'Pembaruan Kebijakan Pajak/Biaya',
                'pesan' => $request->keterangan_perubahan,
                'is_read' => false
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui dan notifikasi dikirim ke seluruh mitra.');
    }
}
