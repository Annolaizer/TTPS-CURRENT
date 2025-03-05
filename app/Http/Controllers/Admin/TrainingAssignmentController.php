<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\Facilitator;
use App\Models\TrainingTeacher;
use App\Models\TrainingFacilitator;
use App\Models\Region;
use App\Models\Organization;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use FPDF;

class TrainingAssignmentController extends Controller
{
    /**
     * Show the training assignment page
     */
    public function show($trainingCode)
    {
        $training = Training::with(['organization', 'subjects', 'ward.district.region'])
            ->where('training_code', $trainingCode)
            ->firstOrFail();
        
        return view('trainings.assign_training', [
            'training' => $training,
            'organizations' => Organization::all(['organization_id', 'name']),
            'regions' => Region::all(['region_id', 'region_name']),
            'subjects' => Subject::all(['subject_id', 'subject_name']),
            'user' => auth()->user()
        ]);
    }

    /**
     * Get available teachers for assignment
     */
    public function getAvailableTeachers(Request $request, $trainingCode)
    {
        Log::info("annolaizer");
        $training = Training::where('training_code', $trainingCode)->firstOrFail();

        Log::info('Training level: ' . $training->education_level);

        // Build query for teachers
        $query = TeacherProfile::where('education_level', $training->education_level)
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
            });

        // Apply region filter if provided
        if ($request->filled('region_id')) {
            $query->whereHas('ward', function($q) use ($request) {
                $q->whereHas('district', function($q) use ($request) {
                    $q->where('region_id', $request->region_id);
                });
            });
        }

        // Get teachers with all necessary relationships
        $teachers = $query->with(['user', 'ward.district.region'])
            ->get();

        // Debug log
        \Log::info('Teachers data:', $teachers->map(function($teacher) {
            return [
                'teacher_id' => $teacher->teacher_id,
                'ward_id' => $teacher->ward_id,
                'ward' => $teacher->ward ? [
                    'ward_id' => $teacher->ward->ward_id,
                    'ward_name' => $teacher->ward->ward_name,
                    'district' => $teacher->ward->district ? [
                        'district_id' => $teacher->ward->district->district_id,
                        'district_name' => $teacher->ward->district->district_name,
                        'region' => $teacher->ward->district->region ? [
                            'region_id' => $teacher->ward->district->region->region_id,
                            'region_name' => $teacher->ward->district->region->region_name
                        ] : null
                    ] : null
                ] : null
            ];
        })->toArray());

        $teachers = $teachers->map(function($teacher) {
            return [
                'id' => $teacher->teacher_id,
                'name' => $teacher->user->name,
                'registration_number' => $teacher->registration_number,
                'education_level' => $teacher->education_level,
                'years_of_experience' => $teacher->years_of_experience,
                'current_school' => $teacher->current_school,
                'ward_name' => $teacher->ward ? $teacher->ward->ward_name : null,
                'district_name' => $teacher->ward && $teacher->ward->district ? $teacher->ward->district->district_name : null,
                'region_name' => $teacher->ward && $teacher->ward->district && $teacher->ward->district->region ? $teacher->ward->district->region->region_name : null
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'teachers' => $teachers,
                'total_teachers' => $teachers->count()
            ]
        ]);
    }

    /**
     * Get available facilitators for assignment
     */
    public function getAvailableFacilitators(Request $request, $trainingCode)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();

        // Get facilitators not already assigned to this training
        $facilitators = User::where('role', 'cpd_facilitator')
            ->where('status', 'active')
            ->whereDoesntHave('facilitatedTrainings', function($query) use ($training) {
                $query->where('ft.training_id', $training->training_id)
                      ->orWhere(function($q) use ($training) {
                          $q->where('ft.start_date', '<=', $training->end_date)
                            ->where('ft.end_date', '>=', $training->start_date);
                      });
            })
            ->get()
            ->map(function($facilitator) {
                return [
                    'id' => $facilitator->user_id,
                    'name' => $facilitator->name,
                    'specialization' => $facilitator->facilitatorProfile->specialization ?? 'N/A',
                    'status' => 'Available'
                ];
            });

        return response()->json($facilitators);
    }

    /**
     * Assign a teacher to a training
     */
    public function assignTeacher(Request $request, $trainingCode)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();
        $teacherId = $request->input('teacher_id');

        // Check if teacher exists and is available
        $teacher = TeacherProfile::findOrFail($teacherId);
        
        // Check if teacher's education level matches training's education level
        if ($teacher->education_level !== $training->education_level) {
            return response()->json(['message' => 'Teacher education level does not match training requirements'], 422);
        }

        // Check if teacher is already assigned
        if ($training->teachers()->where('training_teachers.teacher_id', $teacherId)->exists()) {
            return response()->json(['message' => 'Teacher is already assigned to this training'], 422);
        }

        // Check if maximum participants limit is reached
        if ($training->teachers()->count() >= $training->max_participants) {
            return response()->json(['message' => 'Maximum participants limit reached'], 422);
        }

        // Assign teacher
        $training->teachers()->attach($teacherId, [
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Teacher assigned successfully']);
    }

    /**
     * Assign a facilitator to a training
     */
    public function assignFacilitator(Request $request, $trainingCode)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();
        $facilitatorId = $request->input('facilitator_id');

        // Check if facilitator exists and is available
        $facilitator = User::where('role', 'cpd_facilitator')
            ->where('user_id', $facilitatorId)
            ->where('status', 'active')
            ->firstOrFail();

        // Check if facilitator is already assigned
        if ($training->facilitators()->where('training_facilitators.user_id', $facilitatorId)->exists()) {
            return response()->json(['message' => 'Facilitator is already assigned to this training'], 422);
        }

        // Assign facilitator
        $training->facilitators()->attach($facilitatorId, [
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Facilitator assigned successfully']);
    }

    /**
     * Remove a teacher from a training
     */
    public function removeTeacher(Request $request, $trainingCode, $teacherId)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();
        
        // Remove teacher
        $training->teachers()->detach($teacherId);

        return response()->json(['message' => 'Teacher removed successfully']);
    }

    /**
     * Remove a facilitator from a training
     */
    public function removeFacilitator(Request $request, $trainingCode, $facilitatorId)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();
        
        // Remove facilitator
        $training->facilitators()->detach($facilitatorId);

        return response()->json(['message' => 'Facilitator removed successfully']);
    }

    /**
     * Get assigned teachers for DataTable
     */
    public function getAssignedTeachers($trainingCode)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();

        $teachers = DB::table('training_teachers')
            ->join('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
            ->join('users', 'teacher_profiles.user_id', '=', 'users.user_id')
            ->where('training_teachers.training_id', $training->training_id)
            ->select([
                'users.name',
                'training_teachers.teacher_id',
                'training_teachers.attendance_status',
                'training_teachers.report_file'
            ])
            ->get();

        return response()->json(['data' => $teachers]);
    }

    /**
     * Get assigned facilitators for DataTable
     */
    public function getAssignedFacilitators($trainingCode)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();

        $facilitators = DB::table('training_facilitators')
            ->join('users', 'training_facilitators.user_id', '=', 'users.user_id')
            ->where('training_facilitators.training_id', $training->training_id)
            ->select([
                'users.name',
                'users.user_id',
                'training_facilitators.attendance_status',
                'training_facilitators.report_file'
            ])
            ->get();

        return response()->json(['data' => $facilitators]);
    }

    /**
     * Get all participants (teachers and facilitators) for a specific training
     */
    public function getParticipants($trainingCode)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();

        // Get teachers
        $teachers = DB::table('training_teachers')
            ->join('teacher_profiles', 'training_teachers.teacher_id', '=', 'teacher_profiles.teacher_id')
            ->join('users', 'teacher_profiles.user_id', '=', 'users.user_id')
            ->where('training_teachers.training_id', $training->training_id)
            ->select([
                'training_teachers.id',
                'users.name',
                DB::raw("'Teacher' as type"),
                'training_teachers.attendance_status',
                'training_teachers.report_file',
                'training_teachers.teacher_id as participant_id'
            ]);

        // Get facilitators
        $facilitators = DB::table('training_facilitators')
            ->join('users', 'training_facilitators.user_id', '=', 'users.user_id')
            ->where('training_facilitators.training_id', $training->training_id)
            ->select([
                'training_facilitators.id',
                'users.name',
                DB::raw("'CPD Facilitator' as type"),
                'training_facilitators.attendance_status',
                'training_facilitators.report_file',
                'training_facilitators.user_id as participant_id'
            ]);

        // Combine queries
        $query = $teachers->union($facilitators);

        return DataTables::of($query)
            ->addIndexColumn()
            ->filterColumn('name', function($query, $keyword) {
                $query->whereRaw('LOWER(users.name) LIKE ?', ["%".strtolower($keyword)."%"]);
            })
            ->filterColumn('type', function($query, $keyword) {
                $query->whereRaw("CASE 
                    WHEN training_teachers.id IS NOT NULL THEN 'Teacher'
                    ELSE 'CPD Facilitator'
                END LIKE ?", ["%".strtolower($keyword)."%"]);
            })
            ->filterColumn('attendance_status', function($query, $keyword) {
                $query->whereRaw('LOWER(attendance_status) LIKE ?', ["%".strtolower($keyword)."%"]);
            })
            ->filterColumn('report_file', function($query, $keyword) {
                $query->whereRaw('LOWER(report_file) LIKE ?', ["%".strtolower($keyword)."%"]);
            })
            ->rawColumns(['attendance_status'])
            ->make(true);
    }

    /**
     * Update or create a training phase
     */
    public function updatePhase(Request $request, $trainingCode)
    {
        try {
            Log::info('Starting training phase creation', [
                'training_code' => $trainingCode,
                'request_data' => $request->except(['_token'])
            ]);

            $training = Training::where('training_code', $trainingCode)->firstOrFail();
            
            // Check if current training phase is completed
            if ($training->status !== 'completed') {
                Log::warning('Cannot create phase - training not completed', [
                    'training_code' => $trainingCode,
                    'current_status' => $training->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot create new phase. Current training phase must be completed first.'
                ], 400);
            }
            
            // Validate request
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'required',
                'max_participants' => 'required|integer|min:1',
                'description' => 'required|string',
                'venue_name' => 'required|string'
            ]);
            
            Log::info('Request validation passed', ['validated_data' => $validated]);
            
            // Check for existing training with same phase
            $nextPhaseNumber = ($training->training_phase ?? 0) + 1;
            $existingPhase = Training::where('training_code', 'like', $trainingCode . '%')
                ->where('training_phase', $nextPhaseNumber)
                ->first();
                
            if ($existingPhase) {
                Log::warning('Phase already exists', [
                    'training_code' => $trainingCode,
                    'phase_number' => $nextPhaseNumber
                ]);
                return response()->json([
                    'success' => false,
                    'message' => "Training phase {$nextPhaseNumber} already exists"
                ], 400);
            }

            // Check for any pending or ongoing phases
            $pendingPhase = Training::where('training_code', 'like', $trainingCode . '%')
                ->whereIn('status', ['pending', 'ongoing'])
                ->first();
                
            if ($pendingPhase) {
                Log::warning('Cannot create phase - pending/ongoing phase exists', [
                    'training_code' => $trainingCode,
                    'pending_phase' => $pendingPhase->training_code
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot create new phase. There is already a pending or ongoing phase.'
                ], 400);
            }

            // Generate new training code with phase suffix
            $baseCode = preg_replace('/-P\d+$/', '', $trainingCode); // Remove any existing phase suffix
            $newTrainingCode = $baseCode . '-P' . $nextPhaseNumber;

            Log::info('Creating new training phase', [
                'base_code' => $baseCode,
                'new_code' => $newTrainingCode,
                'phase_number' => $nextPhaseNumber
            ]);

            // Create new training record with incremented phase
            $newTraining = $training->replicate();
            $newTraining->training_code = $newTrainingCode;
            $newTraining->training_phase = $nextPhaseNumber;
            $newTraining->start_date = $request->start_date;
            $newTraining->end_date = $request->end_date;
            $newTraining->start_time = $request->start_time;
            $newTraining->max_participants = $request->max_participants;
            $newTraining->description = $request->description;
            $newTraining->venue_name = $request->venue_name;
            $newTraining->status = 'pending';
            $newTraining->verified_at = null;
            $newTraining->verified_by = null;
            $newTraining->rejected_at = null;
            $newTraining->rejected_by = null;
            $newTraining->rejection_reason = null;
            $newTraining->save();

            Log::info('Training phase created successfully', [
                'training_code' => $newTraining->training_code,
                'phase' => $newTraining->training_phase
            ]);

            return response()->json([
                'success' => true,
                'message' => 'New training phase created successfully',
                'phase' => $newTraining->training_phase
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed while creating training phase', [
                'training_code' => $trainingCode,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            Log::error('Training not found while creating phase', [
                'training_code' => $trainingCode
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Training not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to create training phase', [
                'training_code' => $trainingCode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create training phase: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download participant's report
     */
    public function downloadReport($trainingCode, $participantId)
    {
        $training = Training::where('training_code', $trainingCode)->firstOrFail();
        
        // Try to find in teachers first
        $participant = TrainingTeacher::where('training_id', $training->training_id)
            ->where('teacher_id', $participantId)
            ->first();
            
        if (!$participant) {
            // If not found in teachers, look in facilitators
            $participant = TrainingFacilitator::where('training_id', $training->training_id)
                ->where('user_id', $participantId)
                ->first();
                
            if (!$participant) {
                abort(404, 'Participant not found');
            }
        }
        
        if ($participant->attendance_status !== 'attended') {
            abort(404, 'No report available - participant has not attended the training');
        }

        if (!$participant->report_file || !Storage::disk('public')->exists($participant->report_file)) {
            abort(404, 'Report file not found');
        }

        return Storage::disk('public')->download($participant->report_file, 'training_report.pdf');
    }

    /**
     * Assign multiple participants (teachers and facilitators) to a training
     */
    public function assignParticipants(Request $request, $trainingCode)
    {
        try {
            DB::beginTransaction();
            
            $training = Training::where('training_code', $trainingCode)->firstOrFail();
            $teacherIds = $request->input('teacher_ids', []);
            $facilitatorIds = $request->input('facilitator_ids', []);

            Log::info('Assigning participants to training', [
                'training_code' => $trainingCode,
                'teacher_ids' => $teacherIds,
                'facilitator_ids' => $facilitatorIds
            ]);

            // Get count of currently assigned teachers
            $currentTeacherCount = $training->teachers()->count();
            
            // Count new teachers (excluding already assigned ones)
            $newTeacherCount = count(array_filter($teacherIds, function($teacherId) use ($training) {
                return !$training->teachers()
                    ->where('training_teachers.teacher_id', $teacherId)
                    ->exists();
            }));

            // Validate maximum participants limit for teachers
            if (($currentTeacherCount + $newTeacherCount) > $training->max_participants) {
                Log::warning('Maximum participants limit exceeded', [
                    'training_code' => $trainingCode,
                    'current_teachers' => $currentTeacherCount,
                    'new_teachers' => $newTeacherCount,
                    'maximum_limit' => $training->max_participants
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => sprintf(
                        'Cannot assign %d new teachers. Current teachers: %d. Maximum limit: %d',
                        $newTeacherCount,
                        $currentTeacherCount,
                        $training->max_participants
                    )
                ], 422);
            }

            // Assign teachers
            foreach ($teacherIds as $teacherId) {
                // Skip if teacher is already assigned
                if (!$training->teachers()->where('training_teachers.teacher_id', $teacherId)->exists()) {
                    $training->teachers()->attach($teacherId, [
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    Log::info('Teacher assigned', ['teacher_id' => $teacherId]);
                }
            }

            // Assign facilitators
            foreach ($facilitatorIds as $facilitatorId) {
                // Skip if facilitator is already assigned
                if (!$training->facilitators()->where('training_facilitators.user_id', $facilitatorId)->exists()) {
                    $training->facilitators()->attach($facilitatorId, [
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    Log::info('Facilitator assigned', ['facilitator_id' => $facilitatorId]);
                }
            }

            DB::commit();
            Log::info('Assignment completed successfully');

            return response()->json([
                'status' => 'success',
                'message' => 'Participants assigned successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign participants', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to assign participants: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkMoestAndGovernmentTraining($training)
    {
        // Temporarily bypass authentication for testing
        return true;
    }
}
