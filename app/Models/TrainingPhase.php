<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingPhase extends Model
{
    protected $primaryKey = 'phase_id';
    
    protected $fillable = [
        'training_id',
        'title',
        'description',
        'schedule',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'max_participants'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'max_participants' => 'integer'
    ];

    /**
     * Get the training that owns the phase.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
