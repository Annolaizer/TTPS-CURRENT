<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TrainingTeacher extends Model
{
    protected $table = 'training_teachers';
    
    protected $fillable = [
        'training_id',
        'teacher_id',
        'status',
        'attendance_status',
        'attendance_confirmed',
        'attendance_date',
        'attendance_remarks',
        'report_path',
        'report_remarks',
        'report_submitted_at',
        'accepted_at',
        'rejected_at',
        'invited_at'
    ];

    protected $casts = [
        'attendance_date' => 'datetime',
        'attendance_confirmed' => 'boolean',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'invited_at' => 'datetime',
        'report_submitted_at' => 'datetime'
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

    /**
     * Scope a query to only include pending training teachers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include active training teachers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include rejected training teachers.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the teacher's participation status.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function participationStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'pending') {
                    return 'Invitation Pending';
                }
                
                if ($this->status === 'active') {
                    if ($this->attendance_confirmed) {
                        return 'Attended';
                    }
                    return 'Accepted';
                }
                
                if ($this->status === 'rejected') {
                    return 'Rejected';
                }
                
                return 'Unknown';
            }
        );
    }

    /**
     * Determine if the teacher can accept the training.
     *
     * @return bool
     */
    public function canAccept()
    {
        return $this->status === 'pending';
    }

    /**
     * Determine if the teacher can reject the training.
     *
     * @return bool
     */
    public function canReject()
    {
        return $this->status === 'pending';
    }

    /**
     * Determine if the teacher can confirm attendance.
     *
     * @return bool
     */
    public function canConfirmAttendance()
    {
        return $this->status === 'active' && !$this->attendance_confirmed;
    }
}
