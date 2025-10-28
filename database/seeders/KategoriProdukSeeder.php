<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriProduk; // Pastikan ini di-use
// Kita tidak perlu DB::table() atau Schema:: lagi

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // JANGAN GUNAKAN TRUNCATE KARENA AKAN ERROR
        // DB::table('kategori_produk')->truncate();

        // Buat daftar kategori
        $kategoris = [
            ['nama_kategori' => 'Sayuran'],
            ['nama_kategori' => 'Buah-buahan'],
            ['nama_kategori' => 'Roti & Kue'],
            ['nama_kategori' => 'Minuman'],
            ['nama_kategori' => 'Produk Susu & Olahan'],
            ['nama_kategori' => 'Makanan Kaleng'],
            ['nama_kategori' => 'Lainnya'],
        ];

        // Gunakan firstOrCreate untuk menghindari duplikat dan error
        // Ini akan "membuat jika belum ada"
        foreach ($kategoris as $kategori) {
            KategoriProduk::firstOrCreate(
                ['nama_kategori' => $kategori['nama_kategori']]
            );
        }
    }
}
