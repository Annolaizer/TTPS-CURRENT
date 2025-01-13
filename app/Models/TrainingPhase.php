<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TrainingPhase extends Model
{
    protected $primaryKey = 'phase_id';
    
    protected $fillable = [
        'training_id',
        'phase_code',
        'phase_number',
        'title',
        'description',
        'venue_name',
        'start_date',
        'end_date',
        'duration_days',
        'max_participants',
        'region_id',
        'district_id',
        'ward_id',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'max_participants' => 'integer',
        'duration_days' => 'integer',
        'phase_number' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($phase) {
            if (!$phase->status) {
                $phase->status = 'pending';
            }
        });
    }

    /**
     * Get the training that owns the phase.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

    /**
     * Get the region associated with the phase.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    /**
     * Get the district associated with the phase.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * Get the ward associated with the phase.
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    /**
     * The subjects that belong to the phase.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'phase_subjects', 'phase_id', 'subject_id');
    }
}
