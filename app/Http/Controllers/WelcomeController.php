<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Mitra;
use App\Models\User;
use App\Models\DetailTransaksi;

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
            // Ambil data terlebih dahulu
            $data = DB::table('settings')->where('key', 'visitor_count')->first();
            
            if (!$data) {
                DB::table('settings')->insert([
                    'key' => 'visitor_count',
                    'value' => '1',
                    'label' => 'Total Pengunjung Homepage'
                ]);
                $visitorCount = 1;
            } else {
                $visitorCount = (int) $data->value + 1;
                DB::table('settings')->where('key', 'visitor_count')->update(['value' => (string) $visitorCount]);
            }

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

        return view('welcome', compact('visitorCount', 'mitraCount', 'userCount', 'makananDiselamatkan', 'dbError'));
    }
}