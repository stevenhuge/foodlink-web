<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'title'
    ];

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class);
    }
}
