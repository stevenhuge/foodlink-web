<?php
// app/Models/DetailTransaksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';
    protected $primaryKey = 'detail_id';
    public $timestamps = false; // Tidak ada timestamps di migrasi

    /**
     * === TAMBAHKAN INI JIKA BELUM ADA ===
     * Izinkan field ini untuk diisi saat 'create()'
     */
    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah',
        'harga_saat_transaksi',
    ];
    // ===================================


    // --- RELASI (Biarkan seperti yang sudah Anda buat) ---

    /**
     * Satu Detail Transaksi milik satu Transaksi.
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }

    /**
     * Satu Detail Transaksi merujuk ke satu Produk.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'produk_id');
    }
}
