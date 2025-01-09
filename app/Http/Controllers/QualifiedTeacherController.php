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
            Log::info('Training details:', [
                'code' => $training_code,
                'education_level' => $training->education_level
            ]);

            // Add "Education" suffix if not present
            $educationLevel = str_ends_with($training->education_level, 'Education') 
                ? $training->education_level 
                : $training->education_level . ' Education';
            
            // Get a sample of teacher education levels for debugging
            $sampleLevels = TeacherProfile::select('education_level')
                ->distinct()
                ->get()
                ->pluck('education_level');
            Log::info('Available teacher education levels:', ['levels' => $sampleLevels]);
            
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

            // Apply region filter if provided
            if ($request->filled('region_id')) {
                $query->whereHas('ward.district', function($q) use ($request) {
                    $q->where('region_id', $request->region_id);
                });
            }

            // Get teachers with limit
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

            return response()->json([
                'status' => 'success',
                'data' => [
                    'max_participants' => $training->max_participants,
                    'total_teachers' => $teachers->count(),
                    'teachers' => $teachers
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching qualified teachers: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch qualified teachers',
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ], 500);
        }
    }
}
