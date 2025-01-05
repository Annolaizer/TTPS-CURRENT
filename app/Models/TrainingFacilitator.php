<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingFacilitator extends Model
{
    protected $table = 'training_facilitators';
    
    protected $fillable = [
        'training_id',
        'user_id',
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
     * Get the facilitator (user) for this assignment.
     */
    public function facilitator()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
