<?php

namespace App\Services;

use App\Models\Training;
use App\Models\TrainingLocation;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class TrainingService
{
    /**
     * Get filtered trainings with pagination
     */
    public function getFilteredTrainings(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Training::with(['organization', 'location.ward.district.region', 'subjects']);

        // Apply filters
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (!empty($filters['organization_id'])) {
            $query->byOrganization($filters['organization_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        if (!empty($filters['ownership'])) {
            $query->whereHas('organization', function ($q) use ($filters) {
                $q->where('type', $filters['ownership']);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new training with its relationships
     */
    public function createTraining(array $data): Training
    {
        try {
            DB::beginTransaction();

            // Generate unique training code
            $data['training_code'] = $this->generateTrainingCode();

            // Create training
            $training = Training::create($data);

            // Create location
            if (!empty($data['location'])) {
                $training->location()->create($data['location']);
            }

            // Attach subjects
            if (!empty($data['subjects'])) {
                $training->subjects()->attach($data['subjects']);
            }

            // Attach facilitators
            if (!empty($data['facilitators'])) {
                $training->facilitators()->attach($data['facilitators']);
            }

            DB::commit();
            return $training;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing training
     */
    public function updateTraining(Training $training, array $data): Training
    {
        try {
            DB::beginTransaction();

            // Update training
            $training->update($data);

            // Update location
            if (!empty($data['location'])) {
                $training->location()->updateOrCreate(
                    ['training_id' => $training->training_id],
                    $data['location']
                );
            }

            // Update subjects
            if (isset($data['subjects'])) {
                $training->subjects()->sync($data['subjects']);
            }

            // Update facilitators
            if (isset($data['facilitators'])) {
                $training->facilitators()->sync($data['facilitators']);
            }

            DB::commit();
            return $training->fresh(['location', 'subjects', 'facilitators']);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Change training status
     */
    public function changeStatus(Training $training, string $status, ?string $reason = null): Training
    {
        $training->status = $status;
        if ($status === 'rejected' && $reason) {
            $training->rejection_reason = $reason;
        }
        $training->save();
        return $training;
    }

    /**
     * Generate unique training code
     */
    private function generateTrainingCode(): string
    {
        $prefix = 'TRN';
        $year = date('Y');
        $lastTraining = Training::where('training_code', 'like', "{$prefix}{$year}%")
            ->orderBy('training_code', 'desc')
            ->first();

        if ($lastTraining) {
            $lastNumber = (int) substr($lastTraining->training_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf("%s%s%04d", $prefix, $year, $newNumber);
    }

    /**
     * Get training statistics
     */
    public function getStatistics(): array
    {
        return [
            'total' => Training::count(),
            'by_status' => Training::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'by_phase' => Training::select('training_phase', DB::raw('count(*) as count'))
                ->whereNotNull('training_phase')
                ->groupBy('training_phase')
                ->pluck('count', 'training_phase')
                ->toArray(),
            'by_organization_type' => Training::join('organizations', 'trainings.organization_id', '=', 'organizations.organization_id')
                ->select('organizations.type', DB::raw('count(*) as count'))
                ->groupBy('organizations.type')
                ->pluck('count', 'type')
                ->toArray()
        ];
    }
}
