<?php

namespace App\Jobs;

use App\Models\Training;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateTrainingStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting training status update job');
        
        try {
            $today = Carbon::now()->format('Y-m-d');
            
            // Update trainings that should start today
            // Only verified trainings can become ongoing, and only on their exact start date
            $startingTrainings = Training::where('status', 'verified')
                ->whereNotNull('verified_at')
                ->whereDate('start_date', '=', $today)  // Exactly today's date
                ->get();

            foreach ($startingTrainings as $training) {
                Log::info("Marking training as ongoing (start date reached)", [
                    'training_code' => $training->training_code,
                    'start_date' => $training->start_date,
                    'end_date' => $training->end_date,
                    'verified_at' => $training->verified_at
                ]);
                $training->update(['status' => 'ongoing']);
            }

            // Update trainings that should end today
            // Only ongoing trainings can be completed, and only on their exact end date
            $completedTrainings = Training::where('status', 'ongoing')
                ->whereNotNull('verified_at')
                ->whereDate('end_date', '=', $today)  // Exactly today's date
                ->get();

            foreach ($completedTrainings as $training) {
                Log::info("Marking training as completed (end date reached)", [
                    'training_code' => $training->training_code,
                    'start_date' => $training->start_date,
                    'end_date' => $training->end_date,
                    'verified_at' => $training->verified_at
                ]);
                $training->update(['status' => 'completed']);
            }

            Log::info('Training status update job completed successfully', [
                'date' => $today,
                'trainings_started' => $startingTrainings->count(),
                'trainings_completed' => $completedTrainings->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in training status update job: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
