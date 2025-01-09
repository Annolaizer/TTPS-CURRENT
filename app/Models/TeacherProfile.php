<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Training;
use App\Models\Ward;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'teacher_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'teacher_id',
        'user_id',
        'registration_number',
        'status',
        'education_level',
        'teaching_subject',
        'years_of_experience',
        'current_school',
        'ward_id'
    ];

    protected $casts = [
        'teacher_id' => 'string',
        'user_id' => 'string',
        'years_of_experience' => 'integer',
        'ward_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_teachers', 'teacher_id', 'training_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active' ? 'success' : 'warning';
    }

    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }
}
