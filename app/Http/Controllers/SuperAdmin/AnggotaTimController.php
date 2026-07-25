<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaTim;
use Illuminate\Http\Request;

class AnggotaTimController extends Controller
{
    public function index()
    {
        $anggotaTim = AnggotaTim::orderBy('created_at', 'desc')->get();
        return view('admin.anggota-tim.index', compact('anggotaTim'));
    }

    public function create()
    {
        return view('admin.anggota-tim.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto_url' => 'nullable|url|max:2048',
        ]);

        AnggotaTim::create($request->all());

        return redirect()->route('admin.anggota-tim.index')->with('success', 'Anggota Tim berhasil ditambahkan.');
    }

    public function edit(AnggotaTim $anggotaTim)
    {
        return view('admin.anggota-tim.edit', compact('anggotaTim'));
    }

    public function update(Request $request, AnggotaTim $anggotaTim)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto_url' => 'nullable|url|max:2048',
        ]);

        $anggotaTim->update($request->all());

        return redirect()->route('admin.anggota-tim.index')->with('success', 'Anggota Tim berhasil diperbarui.');
    }

    public function destroy(AnggotaTim $anggotaTim)
    {
        $anggotaTim->delete();
        return redirect()->route('admin.anggota-tim.index')->with('success', 'Anggota Tim berhasil dihapus.');
    }
}
