<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $primaryKey = 'subject_id';
    
    protected $fillable = [
        'subject_name',
        'description'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($subject) {
            // Delete associated programs when a subject is deleted
            $subject->programs()->delete();
        });
    }

    /**
     * Get the trainings that include this subject.
     */
    public function trainings(): BelongsToMany
    {
        return $this->belongsToMany(Training::class, 'training_subjects', 'subject_id', 'training_id');
    }

    /**
     * Get the programs that belong to this subject.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'subject_id', 'subject_id');
    }
}
