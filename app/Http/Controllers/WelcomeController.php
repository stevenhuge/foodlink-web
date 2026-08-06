<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Mitra;
use App\Models\User;
use App\Models\DetailTransaksi;
use App\Models\AnggotaTim;

class WelcomeController extends Controller
{
    public function index() 
    {
        $visitorCount = 1;
        $mitraCount = 0;
        $userCount = 0;
        $makananDiselamatkan = 0;
        $dbError = null;

        try {
            // Increment visitor count setiap kali halaman di-refresh
            $setting = DB::table('settings')->where('key', 'visitor_count')->first();
            if ($setting) {
                $newCount = (int) $setting->value + 1;
                DB::table('settings')->where('key', 'visitor_count')->update(['value' => (string) $newCount]);
            } else {
                $newCount = 1;
                DB::table('settings')->insert(['key' => 'visitor_count', 'value' => '1']);
            }
            Cache::forget('visitor_count_display');

            $visitorCount = Cache::remember('visitor_count_display', 300, function () use ($newCount) {
                return $newCount;
            });

            // Hitung total mitra (Cache 1 jam / 3600 detik)
            $mitraCount = Cache::remember('total_mitra', 3600, function () {
                return Mitra::count();
            });
            
            // Hitung total pengguna (User) (Cache 1 jam)
            $userCount = Cache::remember('total_user', 3600, function () {
                return User::count();
            });
            
            // Hitung total makanan yang diselamatkan (Cache 1 jam)
            $makananDiselamatkan = Cache::remember('total_makanan_diselamatkan', 3600, function () {
                return DetailTransaksi::whereHas('transaksi', function($query) {
                    $query->whereIn('status_pemesanan', ['selesai', 'Selesai', 'SELESAI']);
                })->sum('jumlah');
            });

        } catch (\Exception $e) {
            // Tangkap error jika tabel belum ada atau koneksi database di vercel bermasalah
            $visitorCount = 1;
            $mitraCount = 0;
            $userCount = 0;
            $makananDiselamatkan = 0;
            $dbError = $e->getMessage();
            \Log::error("Visitor Count Error: " . $dbError);
        }

        $anggotaTim = AnggotaTim::orderBy('created_at', 'asc')->get();

        return view('welcome', compact('visitorCount', 'mitraCount', 'userCount', 'makananDiselamatkan', 'dbError', 'anggotaTim'));
    }
}