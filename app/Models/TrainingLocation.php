<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingLocation extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'training_id',
        'ward_id',
        'venue_name'
    ];

    /**
     * Get the training that owns the location.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    /**
     * Get the ward of the location.
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'ward_id');
    }
}
