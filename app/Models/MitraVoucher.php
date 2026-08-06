<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'nama_voucher',
        'kode_voucher',
        'tipe_potongan',
        'nilai_potongan',
        'alokasi',
        'produk_id',
        'minimal_belanja',
        'kuota',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
