<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\TrainingTeacher;
use App\Models\TrainingFacilitator;
use Illuminate\Support\Facades\DB;

class TrainingTRN20250001Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the training
        $training = Training::where('training_code', 'TRN20250001')->firstOrFail();
        
        // Get some teachers
        $teachers = TeacherProfile::with('user')
            ->take(2)
            ->get();
            
        // Get a facilitator
        $facilitator = User::where('role', 'cpd_facilitator')
            ->first();

        echo "Assigning participants to training: TRN20250001\n";
        
        // Assign teachers
        foreach ($teachers as $index => $teacher) {
            TrainingTeacher::updateOrCreate(
                [
                    'training_id' => $training->training_id,
                    'teacher_id' => $teacher->teacher_id,
                ],
                [
                    'status' => 'active',
                    'attendance_status' => $index === 0 ? 'attended' : 'not_started',
                    'report_file' => $index === 0 ? 'test_report.pdf' : null
                ]
            );
            echo "- Assigned teacher: {$teacher->user->name} ({$teacher->teacher_id})\n";
        }

        // Assign facilitator
        TrainingFacilitator::updateOrCreate(
            [
                'training_id' => $training->training_id,
                'user_id' => $facilitator->user_id,
            ],
            [
                'status' => 'active',
                'attendance_status' => 'attended',
                'report_file' => 'test_report.pdf'
            ]
        );
        echo "- Assigned facilitator: {$facilitator->name} ({$facilitator->user_id})\n";
    }
}
