<?php
// app/Models/TopupTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupTransaction extends Model
{
    use HasFactory;

    protected $table = 'topup_transactions';
    protected $primaryKey = 'topup_id';
    public $incrementing = false; // <-- Penting
    protected $keyType = 'string'; // <-- Penting

    /**
     * INI YANG MEMPERBAIKI ERROR MASS ASSIGNMENT
     */
    protected $fillable = [
        'topup_id',
        'user_id',
        'amount',
        'poin_didapat',
        'status',
        'payment_gateway_ref'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
