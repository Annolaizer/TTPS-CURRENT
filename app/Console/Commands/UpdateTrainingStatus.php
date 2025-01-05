<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTrainingStatusJob;
use Illuminate\Console\Command;

class UpdateTrainingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'training:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update training statuses based on start and end dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting training status update...');
        
        try {
            UpdateTrainingStatusJob::dispatch();
            $this->info('Training status update job dispatched successfully');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to dispatch training status update job: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
