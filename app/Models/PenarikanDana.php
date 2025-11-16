<?php
// app/Models/PenarikanDana.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanDana extends Model
{
    use HasFactory;

    /**
     * === PERBAIKAN DI SINI ===
     * Memberi tahu Laravel nama tabel yang benar di database
     * (karena default-nya adalah 'penarikan_danas')
     */
    protected $table = 'penarikan_dana';

    // Ini dari langkah sebelumnya, biarkan saja
    protected $primaryKey = 'penarikan_id';
    protected $fillable = [
        'penarikanable_id',
        'penarikanable_type',
        'rekening_bank_id',
        'jumlah',
        'status',
        'catatan_admin',
        'potongan_pajak',
    ];

    // Ini dari langkah sebelumnya, biarkan saja
    public function penarikanable() {
        return $this->morphTo();
    }
    public function rekeningBank() {
        return $this->belongsTo(RekeningBank::class, 'rekening_bank_id', 'rekening_id');
    }
}
