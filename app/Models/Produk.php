<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';

    // --- TAMBAHAN WAJIB (Berdasarkan Migration Anda) ---
    protected $fillable = [
        'mitra_id',
        'kategori_id',
        'nama_produk',
        'deskripsi',
        'foto_produk',
        'harga_normal',
        'harga_diskon',
        'tipe_penawaran', // 'Jual-Cepat', 'Donasi'
        'stok_awal',
        'stok_tersisa',
        'waktu_kadaluarsa',
        'waktu_ambil_mulai',
        'waktu_ambil_selesai',
        'status_produk', // 'Tersedia', 'Habis', 'Ditarik'
    ];

    // Tipe data untuk kolom waktu (Penting untuk kalender)
    protected $casts = [
        'waktu_kadaluarsa' => 'datetime',
        'waktu_ambil_mulai' => 'datetime',
        'waktu_ambil_selesai' => 'datetime',
    ];
    // --- BATAS TAMBAHAN ---


    // --- RELASI (Sudah Benar) ---
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategori_id', 'kategori_id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id', 'produk_id');
    }

    public function barterDiminta()
    {
        return $this->hasMany(Barter::class, 'produk_diminta_id', 'produk_id');
    }

    public function transaksi()
    {
        return $this->belongsToMany(Transaksi::class, 'detail_transaksi', 'produk_id', 'transaksi_id')
                    ->withPivot('jumlah', 'harga_saat_transaksi');
    }
}
