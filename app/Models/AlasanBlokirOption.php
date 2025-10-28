<?php
// app/Models/AlasanBlokirOption.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlasanBlokirOption extends Model
{
    use HasFactory;
    protected $table = 'alasan_blokir_options';
    protected $primaryKey = 'alasan_id';
    protected $fillable = ['alasan_text'];
    public $timestamps = false;

    // Relasi ke Mitra (Satu alasan bisa dipakai banyak mitra)
    public function mitra() {
        return $this->hasMany(Mitra::class, 'alasan_blokir_option_id', 'alasan_id');
    }
}
