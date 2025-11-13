<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password_hash',
        'nomor_telepon',
        'poin_reward',
        'status_akun',
        'alasan_blokir',
    ];

    protected $hidden = [ 'password_hash', 'remember_token', ];
    protected $casts = [ 'email_verified_at' => 'datetime', ];

    public function getAuthPassword() {
        return $this->password_hash;
    }

    // Relasi Transaksi (Pembelian)
    public function transaksi() {
        return $this->hasMany(Transaksi::class, 'user_id', 'user_id');
    }

    // Relasi Barter (Sudah ada)
    public function barterDiajukan() {
        return $this->hasMany(Barter::class, 'pengaju_user_id', 'user_id');
    }

    // --- RELASI BARU ---
    /**
     * Satu User memiliki banyak riwayat Top-Up.
     */
    public function topups()
    {
        return $this->hasMany(TopupTransaction::class, 'user_id', 'user_id');
    }
}
