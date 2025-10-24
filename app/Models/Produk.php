<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';

    // --- RELASI ---

    /**
     * Satu Produk dimiliki oleh satu Mitra.
     */
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }

    /**
     * Satu Produk termasuk dalam satu Kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id', 'kategori_id');
    }

    /**
     * Satu Produk bisa ada di banyak Detail Transaksi.
     */
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id', 'produk_id');
    }

    /**
     * Satu Produk bisa diminta dalam banyak Barter.
     */
    public function barterDiminta()
    {
        return $this->hasMany(Barter::class, 'produk_diminta_id', 'produk_id');
    }

    /**
     * Relasi many-to-many ke Transaksi (melalui DetailTransaksi).
     */
    public function transaksi()
    {
        return $this->belongsToMany(Transaksi::class, 'detail_transaksi', 'produk_id', 'transaksi_id')
                    ->withPivot('jumlah', 'harga_saat_transaksi'); // Ambil data tambahan
    }
}
