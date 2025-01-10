<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilitatorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'education_level',
        'expertise_area',
        'experience_years',
        'status'
    ];

    /**
     * Get the user that owns the facilitator profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the trainings that this facilitator is assigned to.
     */
    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_facilitators')
            ->withTimestamps()
            ->withPivot(['status', 'assigned_at']);
    }
}
