<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\Training;
use App\Models\TrainingTeacher;
use App\Models\TrainingFacilitator;
use App\Models\User;
use App\Models\TeacherProfile;

// Get the training
$training = Training::where('training_code', 'TRN20250042')->first();

if (!$training) {
    echo "Training not found\n";
    exit;
}

echo "Training found with ID: " . $training->training_id . "\n\n";

// Get teachers
$teachers = TrainingTeacher::where('training_id', $training->training_id)
    ->join('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
    ->join('users', 'teacher_profiles.user_id', '=', 'users.user_id')
    ->select('users.name', 'teacher_profiles.education_level')
    ->get();

echo "Teachers assigned:\n";
foreach ($teachers as $teacher) {
    echo "- {$teacher->name} ({$teacher->education_level})\n";
}

echo "\n";

// Get facilitators
$facilitators = TrainingFacilitator::where('training_id', $training->training_id)
    ->join('users', 'training_facilitators.user_id', '=', 'users.user_id')
    ->select('users.name')
    ->get();

echo "Facilitators assigned:\n";
foreach ($facilitators as $facilitator) {
    echo "- {$facilitator->name}\n";
}
