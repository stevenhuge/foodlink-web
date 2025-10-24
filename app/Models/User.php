<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tentukan primary key kustom.
     */
    protected $primaryKey = 'user_id';

    /**
     * Atribut yang dapat diisi.
     * (Sesuaikan 'name' menjadi 'nama_lengkap' dan 'password' menjadi 'password_hash')
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password_hash',
        'nomor_telepon',
    ];

    /**
     * Atribut yang disembunyikan.
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Jika Anda mengganti 'password' di $hidden,
        // pastikan 'password_hash' ada di sini jika Anda butuh cast
    ];

    /**
     * Override method untuk mendapatkan nama kolom password.
     * INI SANGAT PENTING
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // --- RELASI-RELASI YANG SUDAH DIBUAT ---

    /**
     * Satu User memiliki banyak Transaksi.
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id', 'user_id');
    }

    /**
     * Satu User bisa mengajukan banyak Barter.
     */
    public function barterDiajukan()
    {
        return $this->hasMany(Barter::class, 'pengaju_user_id', 'user_id');
    }
}
