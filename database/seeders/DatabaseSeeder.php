<?php

namespace Database\Seeders;

use App\Models\KategoriUsaha;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        AdminSeeder::class,
        KategoriProdukSeeder::class,
        AlasanBlokirOptionsSeeder::class,
    ]);

    // HAPUS LOGIKA 'if count == 0', GANTI DENGAN INI:

    // 1. Biaya Layanan User (500 Perak)
    Setting::updateOrCreate(
        ['key' => 'biaya_layanan_user'], // Cari berdasarkan key ini
        [
            'label' => 'Biaya Layanan User (Rp)',
            'value' => '500', // Nilai yang Anda inginkan
        ]
    );

    // 2. Potongan Mitra (0.5 Persen)
    Setting::updateOrCreate(
        ['key' => 'biaya_mitra_persen'], // Cari berdasarkan key ini
        [
            'label' => 'Potongan Biaya Mitra (%)',
            'value' => '0.5', // Nilai yang Anda inginkan
        ]
    );

    // Opsional: Hapus key lama 'pajak_ppn' jika masih ada agar database bersih
    Setting::where('key', 'pajak_ppn')->delete();
    Setting::where('key', 'biaya_aplikasi')->delete();
}
}
