<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingTeacher extends Model
{
    protected $table = 'training_teachers';
    
    protected $fillable = [
        'training_id',
        'teacher_id',
        'status',
        'attendance_status',
        'attendance_date',
        'attendance_remarks'
    ];

    protected $casts = [
        'attendance_date' => 'datetime'
    ];

    /**
     * Get the training that owns this assignment.
     */
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id', 'training_id');
    }

    /**
     * Get the teacher profile for this assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(TeacherProfile::class, 'teacher_id', 'teacher_id');
    }
}
