<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin; // <-- Tambahkan ini
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari admin dengan username 'superadmin',
        // jika tidak ada, baru buat. Ini mencegah duplikat.
        Admin::firstOrCreate(
            ['username' => 'superadmin'], // Kunci pencarian
            [
                'nama_lengkap' => 'Super Admin Utama',
                'password_hash' => Hash::make('password12345'), // Ganti password ini!
                'role' => 'SuperAdmin'
            ]
        );
    }
}
