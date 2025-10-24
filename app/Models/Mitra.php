<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <-- UBAH INI
use Illuminate\Notifications\Notifiable;

class Mitra extends Authenticatable // <-- UBAH INI
{
    use HasFactory, Notifiable;

    /**
     * Tentukan tabel dan primary key.
     */
    protected $table = 'mitra';
    protected $primaryKey = 'mitra_id';

    /**
     * Tentukan guard untuk model ini.
     */
    protected $guard = 'mitra';

    /**
     * Atribut yang dapat diisi.
     */
    protected $fillable = [
        'nama_mitra',
        'email_bisnis',
        'nomor_telepon',
        'alamat',
        'deskripsi',
        'password_hash',
    ];

    /**
     * Atribut yang disembunyikan.
     */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Beri tahu Laravel nama kolom password Anda adalah 'password_hash'.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // --- RELASI-RELASI YANG SUDAH DIBUAT ---

    public function produk()
    {
        return $this->hasMany(Produk::class, 'mitra_id', 'mitra_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'mitra_id', 'mitra_id');
    }

    public function barterDiajukan()
    {
        return $this->hasMany(Barter::class, 'pengaju_mitra_id', 'mitra_id');
    }

    public function barterDiterima()
    {
        return $this->hasMany(Barter::class, 'penerima_mitra_id', 'mitra_id');
    }
}
