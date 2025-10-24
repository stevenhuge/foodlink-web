<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// --- TAMBAHKAN DUA BARIS INI ---
use App\Models\Mitra;
use App\Models\User;
// ---------------------------------

class DashboardController extends Controller
{
    public function index()
    {
        // Debug information
        $admin = auth()->guard('admin')->user();
        \Log::info('Admin accessing dashboard:', [
            'admin_id' => $admin->admin_id,
            'role' => $admin->role,
            'name' => $admin->nama_lengkap
        ]);

        // Ambil data untuk statistik
        $jumlahUser = User::count();
        $jumlahMitra = Mitra::count();
        $mitraPending = Mitra::where('status_verifikasi', 'Pending')->count();

        return view('admin.dashboard', compact('jumlahUser', 'jumlahMitra', 'mitraPending'));
    }
}
