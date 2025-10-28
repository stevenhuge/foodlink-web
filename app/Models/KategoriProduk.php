<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $primaryKey = 'kategori_id';
    protected $fillable = ['nama_kategori'];
    public $timestamps = false; // Karena kita tidak menambah timestamps di migrasi

    // --- RELASI ---

    /**
     * Satu Kategori memiliki banyak Produk.
     */
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id', 'kategori_id');
    }
}
