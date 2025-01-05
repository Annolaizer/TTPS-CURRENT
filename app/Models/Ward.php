<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ward_id';
    
    protected $fillable = [
        'ward_name',
        'district_id'
    ];

    /**
     * Get the district that owns the ward.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }

    /**
     * Get the training locations in this ward.
     */
    public function trainingLocations(): HasMany
    {
        return $this->hasMany(TrainingLocation::class, 'ward_id', 'ward_id');
    }
}
