<?php
// app/Models/LogKeuangan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKeuangan extends Model
{
    use HasFactory;
    protected $table = 'log_keuangan';

    protected $fillable = [
        'transaksi_id',
        'penarikan_id', // <-- TAMBAHKAN INI
        'penerima_type',
        'penerima_id',
        'tipe',
        'jumlah'
    ];

    public function penerima() {
        return $this->morphTo();
    }

    // Relasi ke Transaksi (Sudah ada)
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }

    /**
     * === TAMBAHKAN RELASI BARU INI ===
     * Satu log keuangan (pajak) dimiliki oleh satu penarikan.
     */
    public function penarikanDana()
    {
        return $this->belongsTo(PenarikanDana::class, 'penarikan_id', 'penarikan_id');
    }
}
