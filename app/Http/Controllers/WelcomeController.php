<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index() 
    {
        $visitorCount = 1;
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

        } catch (\Exception $e) {
            // Tangkap error jika tabel belum ada atau koneksi database di vercel bermasalah
            $visitorCount = 1;
            $dbError = $e->getMessage();
            \Log::error("Visitor Count Error: " . $dbError);
        }

        return view('welcome', compact('visitorCount', 'dbError'));
    }
}