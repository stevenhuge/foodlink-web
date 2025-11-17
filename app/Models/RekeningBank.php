<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningBank extends Model
{
    use HasFactory;

    protected $table = 'rekening_bank';
    protected $primaryKey = 'rekening_id';
    protected $fillable = ['nama_bank', 'nomor_rekening', 'nama_pemilik'];

    // Fungsi Polymorphic: 1 rekening bisa dimiliki Admin ATAU Mitra
    public function rekeningable() {
        return $this->morphTo();
    }

    public function penarikanDana()
    {
        return $this->hasMany(PenarikanDana::class, 'rekening_bank_id', 'rekening_id');
    }
}
