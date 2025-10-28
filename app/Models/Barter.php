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

    /**
     * Atribut yang boleh diisi.
     * (Sudah disesuaikan dengan migrasi baru)
     */
    protected $fillable = [
        'tipe_barter',
        'pengaju_user_id',
        'pengaju_mitra_id',
        'penerima_mitra_id',
        'produk_diminta_id',
        'status_barter',

        // Opsi 1
        'produk_ditawarkan_id',
        'jumlah_ditawarkan',

        // Opsi 2
        'nama_barang_manual',
        'deskripsi_barang_manual',
        'foto_barang_manual',
        'bukti_struk',
    ];

    // --- RELASI (Sudah Benar dari file Anda) ---

    public function pengajuUser()
    {
        return $this->belongsTo(User::class, 'pengaju_user_id', 'user_id');
    }

    public function pengajuMitra()
    {
        return $this->belongsTo(Mitra::class, 'pengaju_mitra_id', 'mitra_id');
    }

    public function penerimaMitra()
    {
        return $this->belongsTo(Mitra::class, 'penerima_mitra_id', 'mitra_id');
    }

    public function produkDiminta()
    {
        return $this->belongsTo(Produk::class, 'produk_diminta_id', 'produk_id');
    }

    /**
     * Relasi baru: Produk yang ditawarkan (Opsi 1).
     */
    public function produkDitawarkan()
    {
        return $this->belongsTo(Produk::class, 'produk_ditawarkan_id', 'produk_id');
    }
}
