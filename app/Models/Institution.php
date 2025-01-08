<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'institution_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'registration_number',
        'name',
        'type',
        'region',
        'district',
        'ward_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
    public function teachers()
    {
        return $this->hasMany(TeacherProfile::class, 'institution_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    // Accessors
    public function getTeachersCountAttribute()
    {
        return $this->teachers()->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
