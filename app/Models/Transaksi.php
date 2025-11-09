<?php
// app/Models/Transaksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'transaksi_id';

    const CREATED_AT = 'waktu_pemesanan';
    const UPDATED_AT = null;

    /**
     * === PERBAIKAN DI SINI ===
     * Izinkan 'total_harga' (kolom lama) untuk diisi.
     */
    protected $fillable = [
        'user_id',
        'mitra_id',
        'total_harga', // <-- TAMBAHKAN INI
        'total_harga_poin',
        'kode_unik_pengambilan',
        'status',
    ];
    // =======================


    // --- RELASI (Tidak berubah) ---
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function mitra() {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }
    public function detailTransaksi() {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id', 'transaksi_id');
    }
    public function produk() {
        return $this->belongsToMany(Produk::class, 'detail_transaksi', 'transaksi_id', 'produk_id')
                    ->withPivot('jumlah', 'harga_saat_transaksi');
    }
}
