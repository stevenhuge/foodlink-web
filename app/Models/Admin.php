<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tentukan guard untuk model ini.
     */
    protected $guard = 'admin';

    /**
     * Tentukan primary key kustom.
     */
    protected $primaryKey = 'admin_id';

    /**
     * Atribut yang dapat diisi.
     */
    protected $fillable = [
        'nama_lengkap',
        'username',
        'password_hash',
        'role',
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

    // --- TAMBAHKAN FUNGSI INI UNTUK MENONAKTIFKAN REMEMBER TOKEN ---
    /**
     * Beri tahu Laravel bahwa model ini tidak menggunakan 'remember_token'.
     */
    public function getRememberTokenName()
    {
        return null;
    }

    /**
     * Check if the admin has a specific role
     */
    public function hasRole($role)
    {
        return trim($this->role) === $role;
    }

    /**
     * Check if the admin is a super admin
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('SuperAdmin');
    }
    // -----------------------------------------------------------------
}
