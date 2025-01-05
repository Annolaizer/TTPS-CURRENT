<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Training;
use App\Models\TeacherProfile;

class TestTeacherAssignment extends Command
{
    protected $signature = 'test:teacher-assignment';
    protected $description = 'Test teacher assignment functionality';

    public function handle()
    {
        $this->info('Testing teacher assignment...');
        
        // Get a Primary Education training
        $training = Training::where('education_level', 'Primary Education')->first();
        if (!$training) {
            $this->error('No Primary Education training found');
            return;
        }
        
        $this->info("Found training: {$training->title} (ID: {$training->training_id})");
        
        // Get available teachers
        $normalizedEducationLevel = str_replace(' Education', '', $training->education_level);
        
        $teachers = TeacherProfile::where(function($q) use ($normalizedEducationLevel) {
                $q->where('education_level', $normalizedEducationLevel)
                  ->orWhere('education_level', $normalizedEducationLevel . ' Education');
            })
            ->whereHas('user', function($query) {
                $query->where('status', 'active')
                      ->where('role', 'teacher');
            })
            ->whereDoesntHave('trainings', function($query) use ($training) {
                $query->where('trainings.training_id', $training->training_id)
                      ->orWhere(function($q) use ($training) {
                          $q->where('trainings.start_date', '<=', $training->end_date)
                            ->where('trainings.end_date', '>=', $training->start_date);
                      });
            })
            ->with('user')
            ->get();
            
        $this->info("Available teachers: {$teachers->count()}");
        
        foreach ($teachers as $teacher) {
            $this->info("- {$teacher->user->name} (Education Level: {$teacher->education_level})");
        }
    }
}
