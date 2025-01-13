<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Training;
use App\Models\TeacherProfile;

use Illuminate\Http\Request;

class QualifiedTeacherController extends Controller
{
    public function index(Request $request, $training_code)
    {
        try {
            // Get the training details
            $training = Training::where('training_code', $training_code)->firstOrFail();
            
            // Debug logging
            Log::info('Fetching qualified teachers', [
                'training_code' => $training_code,
                'education_level' => $training->education_level,
                'start_date' => $training->start_date,
                'end_date' => $training->end_date,
                'region_filter' => $request->region_id
            ]);

            // Add "Education" suffix if not present
            $educationLevel = str_ends_with($training->education_level, 'Education') 
                ? $training->education_level 
                : $training->education_level . ' Education';
            
            // Build the base query
            $query = TeacherProfile::with([
                'user',
                'ward.district.region'
            ])
            ->where('education_level', $educationLevel)
            ->whereIn('status', ['active', 'pending', null])
            ->whereHas('user', function ($query) {
                $query->where('role', 'teacher')
                      ->where('status', 'active');
            });

            // Check for schedule conflicts
            $query->whereDoesntHave('trainings', function ($query) use ($training) {
                $query->where('trainings.start_date', '<=', $training->end_date)
                      ->where('trainings.end_date', '>=', $training->start_date);
            });

            // Check for participation limit in the same training this year
            $query->whereRaw('(SELECT COUNT(*) FROM training_teachers 
                             INNER JOIN trainings ON training_teachers.training_id = trainings.training_id 
                             WHERE training_teachers.teacher_id = teacher_profiles.teacher_id 
                             AND trainings.training_code LIKE ? 
                             AND YEAR(trainings.start_date) = YEAR(?) ) < 2', 
                             [substr($training->training_code, 0, -4) . '%', $training->start_date]);

            // Apply region filter if provided
            if ($request->filled('region_id')) {
                $query->whereHas('ward.district', function($q) use ($request) {
                    $q->where('region_id', $request->region_id);
                });
            }

            // Get teachers with limit and map the results
            $teachers = $query->take($training->max_participants)
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->teacher_id,
                        'registration_number' => $teacher->registration_number,
                        'name' => $teacher->user->name,
                        'email' => $teacher->user->email,
                        'gender' => $teacher->user->gender ?? null,
                        'status' => $teacher->status,
                        'education_level' => $teacher->education_level ?? null,
                        'teaching_subject' => $teacher->teaching_subject ?? null,
                        'years_of_experience' => $teacher->years_of_experience ?? null,
                        'current_school' => $teacher->current_school ?? null,
                        'region_id' => $teacher->ward->district->region->region_id ?? null,
                        'region_name' => $teacher->ward->district->region->region_name ?? null,
                        'district_id' => $teacher->ward->district->district_id ?? null,
                        'district_name' => $teacher->ward->district->district_name ?? null,
                        'ward_id' => $teacher->ward->ward_id ?? null,
                        'ward_name' => $teacher->ward->ward_name ?? null
                    ];
                });

            // Log the results
            Log::info('Qualified teachers fetched successfully', [
                'training_code' => $training_code,
                'total_teachers' => $teachers->count(),
                'max_participants' => $training->max_participants
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'max_participants' => $training->max_participants,
                    'total_teachers' => $teachers->count(),
                    'teachers' => $teachers
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching qualified teachers', [
                'training_code' => $training_code ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'region_filter' => $request->region_id ?? null
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to fetch qualified teachers. ' . ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Training not found.' 
                    : 'Please try again or contact support if the issue persists.'),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
