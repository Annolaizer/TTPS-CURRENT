<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingEnrollment extends Model
{
    protected $table = 'training_enrollments';
    protected $primaryKey = 'enrollment_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'enrollment_id',
        'teacher_id',
        'training_id',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'enrollment_id' => 'string',
        'teacher_id' => 'string',
        'training_id' => 'integer',
    ];

    /**
     * Get the teacher that owns the enrollment.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id', 'teacher_id');
    }

    /**
     * Get the training that owns the enrollment.
     */
    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }
}
