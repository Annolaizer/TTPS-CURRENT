<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TeacherProfile;

class CheckTeacherProfiles extends Command
{
    protected $signature = 'check:teachers';
    protected $description = 'Check teacher profiles in the database';

    public function handle()
    {
        $this->info('Checking teacher profiles...');
        
        $count = TeacherProfile::count();
        $this->info("Total teacher profiles: {$count}");
        
        $educationLevels = TeacherProfile::distinct()->pluck('education_level')->toArray();
        $this->info('Education levels: ' . implode(', ', $educationLevels));
        
        $primaryTeachers = TeacherProfile::where('education_level', 'Primary Education')->count();
        $this->info("Teachers with Primary Education: {$primaryTeachers}");
    }
}
