<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\TrainingTeacher;
use App\Models\TrainingFacilitator;

class TrainingParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing training IDs
        $trainings = Training::take(3)->get();
        
        // Get some existing teachers
        $teachers = TeacherProfile::take(3)->get();
        
        // Get some existing facilitators
        $facilitators = User::where('role', 'cpd_facilitator')->take(3)->get();

        foreach ($trainings as $index => $training) {
            echo "Assigning participants to training: {$training->training_code}\n";
            
            // Assign 2 teachers to each training
            foreach ($teachers->take(2) as $teacher) {
                TrainingTeacher::create([
                    'training_id' => $training->training_id,
                    'teacher_id' => $teacher->teacher_id,
                    'status' => 'active'
                ]);
                echo "- Assigned teacher: {$teacher->teacher_id}\n";
            }

            // Assign 1 facilitator to each training
            TrainingFacilitator::create([
                'training_id' => $training->training_id,
                'user_id' => $facilitators[$index]->user_id,
                'status' => 'active'
            ]);
            echo "- Assigned facilitator: {$facilitators[$index]->user_id}\n";
        }
    }
}
