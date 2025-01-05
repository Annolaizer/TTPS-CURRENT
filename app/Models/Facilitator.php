<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Facilitator extends Model
{
    use HasFactory;

    protected $primaryKey = 'facilitator_id';
    protected $keyType = 'string';

    protected $fillable = [
        'facilitator_id',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
