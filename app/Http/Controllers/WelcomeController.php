<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WelcomeController extends Controller
{
    public function index() 
    {
        try {
            // 1. Cek & Buat Tabel jika belum ada
            if (!Schema::hasTable('settings')) {
                Schema::create('settings', function ($table) {
                    $table->id();
                    $table->string('key')->unique();
                    $table->bigInteger('value')->default(0);
                    $table->timestamps();
                });
                
                DB::table('settings')->insert(['key' => 'visitor_count', 'value' => 0]);
            }

            // 2. Gunakan update Or Insert agar lebih pasti
            // Ini akan menambah 1 ke nilai yang sudah ada
            DB::table('settings')
                ->where('key', 'visitor_count')
                ->increment('value');

            // 3. Ambil nilai terbaru
            $data = DB::table('settings')->where('key', 'visitor_count')->first();
            $visitorCount = $data ? $data->value : 1;

        } catch (\Exception $e) {
            // Jika database error (misal login salah), 
            // kita tampilkan angka 1 agar halaman tidak crash
            $visitorCount = 1;
            
            // Log errornya agar bisa dicek di Vercel Logs
            \Log::error("Visitor Count Error: " . $e->getMessage());
        }

        return view('welcome', compact('visitorCount'));
    }
}