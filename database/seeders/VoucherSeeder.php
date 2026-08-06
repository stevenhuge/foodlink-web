<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MitraVoucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MitraVoucher::updateOrCreate(
            ['kode_voucher' => 'DISKON10K'],
            [
                'mitra_id'        => 1,
                'nama_voucher'    => 'Voucher Hemat Rp 10.000',
                'tipe_potongan'   => 'nominal',
                'nilai_potongan'  => 10000,
                'alokasi'         => 'semua_menu',
                'produk_id'       => null,
                'minimal_belanja' => 20000,
                'kuota'           => 100,
                'tanggal_mulai'   => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addMonths(1),
                'status'          => 'aktif',
            ]
        );

        MitraVoucher::updateOrCreate(
            ['kode_voucher' => 'FOODLINK20'],
            [
                'mitra_id'        => 1,
                'nama_voucher'    => 'Diskon 20%',
                'tipe_potongan'   => 'persentase',
                'nilai_potongan'  => 20,
                'alokasi'         => 'semua_menu',
                'produk_id'       => null,
                'minimal_belanja' => 15000,
                'kuota'           => 50,
                'tanggal_mulai'   => Carbon::now()->subDays(1),
                'tanggal_selesai' => Carbon::now()->addMonths(1),
                'status'          => 'aktif',
            ]
        );
    }
}
