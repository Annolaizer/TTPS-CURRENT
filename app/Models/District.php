<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'district_id';
    
    protected $fillable = [
        'district_name',
        'region_id'
    ];

    /**
     * Get the region that owns the district.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * Get the wards in this district.
     */
    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class, 'district_id', 'district_id');
    }
}
