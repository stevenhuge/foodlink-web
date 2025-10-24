<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barter extends Model
{
    use HasFactory;

    protected $table = 'barter';
    protected $primaryKey = 'barter_id';

    const CREATED_AT = 'waktu_pengajuan';
    // UPDATED_AT akan otomatis dikelola oleh timestamps()

    // --- RELASI ---

    /**
     * Barter ini diajukan oleh (User).
     */
    public function pengajuUser()
    {
        return $this->belongsTo(User::class, 'pengaju_user_id', 'user_id');
    }

    /**
     * Barter ini diajukan oleh (Mitra).
     */
    public function pengajuMitra()
    {
        return $this->belongsTo(Mitra::class, 'pengaju_mitra_id', 'mitra_id');
    }

    /**
     * Barter ini ditujukan kepada (Mitra).
     */
    public function penerimaMitra()
    {
        return $this->belongsTo(Mitra::class, 'penerima_mitra_id', 'mitra_id');
    }

    /**
     * Barter ini meminta (Produk).
     */
    public function produkDiminta()
    {
        return $this->belongsTo(Produk::class, 'produk_diminta_id', 'produk_id');
    }
}
