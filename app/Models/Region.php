<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'region_id';
    
    protected $fillable = [
        'region_name'
    ];

    /**
     * Get the districts in this region.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'region_id', 'region_id');
    }
}
