<?php
// app/Models/LogKeuangan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKeuangan extends Model
{
    use HasFactory;

    /**
     * === PERBAIKAN DI SINI ===
     * Memberi tahu Laravel nama tabel yang benar di database
     * (karena default-nya adalah 'log_keuangans')
     */
    protected $table = 'log_keuangan';

    // Ini dari langkah sebelumnya, biarkan saja
    protected $fillable = [
        'transaksi_id',
        'penerima_type',
        'penerima_id',
        'tipe',
        'jumlah'
    ];

    // Ini dari langkah sebelumnya, biarkan saja
    public function penerima() {
        return $this->morphTo();
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'transaksi_id');
    }
}
