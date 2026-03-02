<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            // Coba update (tambahkan 1)
            $updated = DB::table('settings')
                ->where('key', 'visitor_count')
                ->increment('value');

            // Jika row belum ada (return 0), insert row pertama
            if ($updated === 0) {
                DB::table('settings')->insert([
                    'key' => 'visitor_count',
                    'value' => 1,
                    'label' => 'Total Pengunjung Homepage'
                ]);
                $visitorCount = 1;
            } else {
                // Ambil nilai terbaru
                $data = DB::table('settings')->where('key', 'visitor_count')->first();
                $visitorCount = $data ? $data->value : 1;
            }

            // Hitung total mitra
            $mitraCount = Mitra::count();
            
            // Hitung total pengguna (User)
            $userCount = User::count();
            
            // Hitung total makanan yang diselamatkan (dari jumlah item di detail transaksi)
            // Hanya menghitung dari transaksi yang statusnya 'Selesai'
            $makananDiselamatkan = DetailTransaksi::whereHas('transaksi', function($query) {
                $query->where('status_pemesanan', 'Selesai');
            })->sum('jumlah');

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