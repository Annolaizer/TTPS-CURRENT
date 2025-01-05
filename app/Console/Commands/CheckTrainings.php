<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Training;

class CheckTrainings extends Command
{
    protected $signature = 'check:trainings';
    protected $description = 'Check trainings in the database';

    public function handle()
    {
        $this->info('Checking trainings...');
        
        $count = Training::count();
        $this->info("Total trainings: {$count}");
        
        $educationLevels = Training::distinct()->pluck('education_level')->toArray();
        $this->info('Education levels: ' . implode(', ', $educationLevels));
        
        $primaryTrainings = Training::where('education_level', 'Primary Education')->count();
        $this->info("Trainings for Primary Education: {$primaryTrainings}");
    }
}
