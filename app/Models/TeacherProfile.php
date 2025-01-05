<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Training;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $primaryKey = 'teacher_id';
    protected $keyType = 'string';

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
}
