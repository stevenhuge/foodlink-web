<?php
// database/seeders/AlasanBlokirOptionsSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AlasanBlokirOption;
use Illuminate\Support\Facades\DB; // <-- Perlu untuk DB::table jika dipakai

class AlasanBlokirOptionsSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // DB::table('alasan_blokir_options')->truncate(); // <-- HAPUS ATAU KOMENTARI BARIS INI

        $alasan = [
            ['alasan_text' => 'Pelanggaran Ketentuan Layanan'],
            ['alasan_text' => 'Aktivitas Mencurigakan/Spam'],
            ['alasan_text' => 'Laporan Pengguna Lain'],
            ['alasan_text' => 'Konten Tidak Pantas'],
            ['alasan_text' => 'Penipuan atau Upaya Penipuan'],
            ['alasan_text' => 'Akun Ganda/Duplikat'],
        ];

        // Gunakan insert() atau create() untuk menambahkan data
        // insert() lebih cepat tapi tidak memicu event model
        // create() lebih aman jika ada logic di model

        // Cara 1: Gunakan insert() - Abaikan jika sudah ada (karena unique constraint)
        try {
             AlasanBlokirOption::insert($alasan);
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error jika terjadi duplikasi (kode 23000) atau error lain
            if ($e->getCode() !== '23000') {
                // Jika bukan error duplikasi, tampilkan errornya
                $this->command->error('Error seeding AlasanBlokirOptions: ' . $e->getMessage());
            }
            // Jika error duplikasi, abaikan saja karena data sudah ada
        }


        // Cara 2: Gunakan firstOrCreate() - Lebih aman tapi lebih lambat
        // foreach ($alasan as $item) {
        //     AlasanBlokirOption::firstOrCreate(['alasan_text' => $item['alasan_text']]);
        // }

         $this->command->info('AlasanBlokirOptions seeded successfully (or already existed).');
    }
}
