<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyanggahanMitra extends Model
{
    use HasFactory;

    protected $table = 'penyanggahan_mitra';
    protected $primaryKey = 'sanggahan_id';

    protected $fillable = [
        'mitra_id',
        'alasan_sanggah',
        'bukti_files',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'bukti_files' => 'array', // Agar otomatis jadi array saat diambil
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }
}
