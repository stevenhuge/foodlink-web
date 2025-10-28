<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriUsaha extends Model
{
    use HasFactory;

    protected $table = 'kategori_usaha';
    protected $primaryKey = 'kategori_usaha_id';
    protected $fillable = ['nama_kategori'];
    public $timestamps = false; // No timestamps

    /**
     * Relationship: One category has many Mitra.
     */
    public function mitra()
    {
        return $this->hasMany(Mitra::class, 'kategori_usaha_id', 'kategori_usaha_id');
    }
}
