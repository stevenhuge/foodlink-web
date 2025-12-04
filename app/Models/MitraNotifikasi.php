<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraNotifikasi extends Model
{
    protected $table = 'mitra_notifikasi';
    protected $guarded = [];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id');
    }
}
