<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\User;
use App\Models\Facilitator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class QualifiedFacilitatorsController extends Controller
{
    public function index(Request $request, $training_code)
    {
        try {
            // Get the training details
            $training = Training::where('training_code', $training_code)->firstOrFail();
            
            // Debug logging
            Log::info('Training details:', [
                'code' => $training_code,
                'start_date' => $training->start_date,
                'end_date' => $training->end_date
            ]);

            // Debug: Check total facilitators before any filtering
            $totalFacilitators = User::where('role', 'cpd_facilitator')->count();
            Log::info('Total CPD Facilitators:', ['count' => $totalFacilitators]);

            // Get all facilitators who are qualified and active
            $query = User::query()
                ->select('users.*')
                ->join('facilitators', 'users.user_id', '=', 'facilitators.user_id')
                ->where('users.role', 'cpd_facilitator')  
                ->where('users.status', 'active');

            // Debug: Log the base query results
            $baseQueryResults = $query->get();
            Log::info('Base query results:', [
                'count' => $baseQueryResults->count(),
                'facilitators' => $baseQueryResults->pluck('name', 'user_id')
            ]);

            // Check facilitator availability based on training schedule
            $unavailableFacilitatorIds = DB::table('training_facilitators')
                ->join('trainings', 'training_facilitators.training_id', '=', 'trainings.training_id')
                ->where(function($query) use ($training) {
                    $query->where(function($q) use ($training) {
                        // Check if there's any overlap with existing training schedules
                        $q->whereBetween('trainings.start_date', [$training->start_date, $training->end_date])
                          ->orWhereBetween('trainings.end_date', [$training->start_date, $training->end_date])
                          ->orWhere(function($q) use ($training) {
                              $q->where('trainings.start_date', '<=', $training->start_date)
                                ->where('trainings.end_date', '>=', $training->end_date);
                          });
                    });
                })
                ->where('training_facilitators.status', 'active')
                ->pluck('training_facilitators.user_id');

            // Debug: Log unavailable facilitators
            Log::info('Unavailable facilitators:', [
                'count' => $unavailableFacilitatorIds->count(),
                'ids' => $unavailableFacilitatorIds
            ]);

            // Exclude unavailable facilitators
            $query->whereNotIn('users.user_id', $unavailableFacilitatorIds);

            // Get the final list of available facilitators with their details
            $availableFacilitators = $query->with(['facilitator' => function($query) {
                    $query->select('user_id', 'specialization', 'qualifications', 'registration_number');
                }])
                ->get();

            // Debug: Log final results
            Log::info('Final available facilitators:', [
                'count' => $availableFacilitators->count(),
                'facilitators' => $availableFacilitators->map(function($f) {
                    return [
                        'name' => $f->name,
                        'user_id' => $f->user_id,
                        'has_facilitator_profile' => $f->facilitator !== null
                    ];
                })
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $availableFacilitators
            ]);

        } catch (\Exception $e) {
            Log::error('Error in QualifiedFacilitatorsController:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching qualified facilitators'
            ], 500);
        }
    }
}
