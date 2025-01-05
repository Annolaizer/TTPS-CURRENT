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
use Yajra\DataTables\DataTables;

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
        $training = Training::where('training_code', $trainingCode)->firstOrFail();

        // Get teachers matching education level and not already assigned
        $teachers = TeacherProfile::where('education_level', $training->education_level)
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
            ->get()
            ->map(function($teacher) {
                return [
                    'id' => $teacher->teacher_id,
                    'name' => $teacher->user->name,
                    'education_level' => $teacher->education_level,
                    'status' => 'Available'
                ];
            });

        return response()->json($teachers);
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
                $query->where('trainings.training_id', $training->training_id)
                      ->orWhere(function($q) use ($training) {
                          $q->where('trainings.start_date', '<=', $training->end_date)
                            ->where('trainings.end_date', '>=', $training->start_date);
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
        if ($training->teachers()->where('teacher_id', $teacherId)->exists()) {
            return response()->json(['message' => 'Teacher is already assigned to this training'], 422);
        }

        // Check if maximum participants limit is reached
        if ($training->teachers()->count() >= $training->maximum_participants) {
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
        if ($training->facilitators()->where('user_id', $facilitatorId)->exists()) {
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
            ->rawColumns(['attendance_status'])
            ->make(true);
    }

    /**
     * Update or create a training phase
     */
    public function updatePhase(Request $request, $trainingCode)
    {
        try {
            $training = Training::where('training_code', $trainingCode)->firstOrFail();
            
            // Check if current training phase is completed
            if ($training->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot create new phase. Current training phase must be completed first.'
                ], 400);
            }
            
            // Validate request
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'required',
                'max_participants' => 'required|integer|min:1',
                'description' => 'required|string',
                'venue_name' => 'required|string'
            ]);
            
            // Check for existing training with same phase
            $existingPhase = Training::where('training_code', 'like', $trainingCode . '%')
                ->where('training_phase', ($training->training_phase ?? 0) + 1)
                ->first();
                
            if ($existingPhase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Training phase ' . ($training->training_phase ?? 0) + 1 . ' already exists'
                ], 400);
            }

            // Check for any pending or ongoing phases
            $pendingPhase = Training::where('training_code', 'like', $trainingCode . '%')
                ->whereIn('status', ['pending', 'ongoing'])
                ->first();
                
            if ($pendingPhase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot create new phase. There is already a pending or ongoing phase.'
                ], 400);
            }

            // Generate new training code with phase suffix
            $baseCode = preg_replace('/-P\d+$/', '', $trainingCode); // Remove any existing phase suffix
            $newTrainingCode = $baseCode . '-P' . (($training->training_phase ?? 0) + 1);

            // Create new training record with incremented phase
            $newTraining = $training->replicate();
            $newTraining->training_code = $newTrainingCode;
            $newTraining->training_phase = ($training->training_phase ?? 0) + 1;
            $newTraining->start_date = $request->start_date;
            $newTraining->end_date = $request->end_date;
            $newTraining->start_time = $request->start_time;
            $newTraining->max_participants = $request->max_participants;
            $newTraining->description = $request->description;
            $newTraining->venue_name = $request->venue_name;
            $newTraining->status = 'pending'; // Set default status to pending
            $newTraining->verified_at = null;
            $newTraining->verified_by = null;
            $newTraining->rejected_at = null;
            $newTraining->rejected_by = null;
            $newTraining->rejection_reason = null;
            $newTraining->save();

            return response()->json([
                'success' => true,
                'message' => 'New training phase created successfully',
                'phase' => $newTraining->training_phase
            ]);
        } catch (\Exception $e) {
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
        
        if ($participant->attendance_status !== 'attended' || !$participant->report_file) {
            abort(404, 'No report file available for this participant');
        }
        
        // Generate a dynamic PDF report
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="training_report.pdf"',
        ];
        
        // Create PDF using FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        
        // Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Training Participant Report', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Training Details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Training Code:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $trainingCode, 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Training Title:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $training->title, 0, 1);
        
        // Participant Details
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Participant ID:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $participantId, 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Type:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $participant instanceof TrainingTeacher ? 'Teacher' : 'CPD Facilitator', 0, 1);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Status:', 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, ucfirst($participant->attendance_status), 0, 1);
        
        // Generated timestamp
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Generated on: ' . now()->format('Y-m-d H:i:s'), 0, 1, 'R');
        
        return response($pdf->Output('S'), 200, $headers);
    }

    private function checkMoestAndGovernmentTraining($training)
    {
        // Temporarily bypass authentication for testing
        return true;
    }
}
