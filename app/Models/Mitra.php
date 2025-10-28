<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- Pastikan ini ada

class Mitra extends Authenticatable
{
    // Tambahkan HasApiTokens di sini
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tentukan tabel dan primary key.
     */
    protected $table = 'mitra'; // <-- Ini dari kode Anda (Bagus!)
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
        'status_verifikasi', // <-- Saya tambahkan ini dari kode saya sebelumnya
        'status_akun',
        'alasan_blokir_option_id',
        'kategori_usaha_id', // <-- Tambahkan ini untuk relasi kategori usaha
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

    // --- INI PERBAIKAN UNTUK ERROR 'REMEMBER TOKEN' ---
    /**
     * Beri tahu Laravel bahwa model ini tidak menggunakan 'remember_token'.
     */
    public function getRememberTokenName()
    {
        return null;
    }
    // ---------------------------------------------------

    // --- RELASI-RELASI YANG SUDAH ANDA BUAT ---

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

    public function kategoriUsaha()
    {
        return $this->belongsTo(KategoriUsaha::class, 'kategori_usaha_id', 'kategori_usaha_id');
    }

    public function alasanBlokir() {
        return $this->belongsTo(AlasanBlokirOption::class, 'alasan_blokir_option_id', 'alasan_id');
    }
}
