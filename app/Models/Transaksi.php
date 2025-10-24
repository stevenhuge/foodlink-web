<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    const CREATED_AT = 'waktu_pemesanan';
    const UPDATED_AT = null; // Tidak ada updated_at di migrasi

    // --- RELASI ---

    /**
     * Satu Transaksi dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Satu Transaksi dimiliki oleh satu Mitra.
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }

    /**
     * Satu Transaksi memiliki banyak Detail Transaksi.
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'transaksi_id');
    }

    /**
     * Relasi many-to-many ke Produk (melalui DetailTransaksi).
     */
    public function produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_transaksi', 'transaksi_id', 'produk_id')
                    ->withPivot('jumlah', 'harga_saat_transaksi'); // Ambil data tambahan
    }
}
