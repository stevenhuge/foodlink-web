<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaTim extends Model
{
    protected $fillable = [
        'nama',
        'jabatan',
        'foto_url',
    ];
}
