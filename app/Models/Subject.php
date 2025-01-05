<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'subject_id';
    
    protected $fillable = [
        'subject_name'
    ];

    /**
     * Get the trainings that include this subject.
     */
    public function trainings(): BelongsToMany
    {
        return $this->belongsToMany(Training::class, 'training_subjects', 'subject_id', 'training_id');
    }
}
