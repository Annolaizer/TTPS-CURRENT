<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings attended by the authenticated teacher.
     */
    public function index(Request $request)
    {
        // Check if teacher has completed their profile
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
        
        if (!$teacherProfile) {
            return redirect()->route('teacher.profile.setup')
                           ->with('warning', 'Please complete your profile first.');
        }
        
        $query = Training::query()
            ->join('training_teachers', 'trainings.training_id', '=', 'training_teachers.training_id')
            ->join('organizations', 'trainings.organization_id', '=', 'organizations.organization_id')
            ->where('training_teachers.teacher_id', $teacherProfile->teacher_id)
            ->whereIn('training_teachers.status', ['active', 'accepted', 'rejected']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('trainings.status', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('trainings.start_date', $request->date);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('trainings.title', 'like', '%' . $request->search . '%')
                  ->orWhere('organizations.name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Get trainings with additional information
        $trainings = $query->select('trainings.*', 'organizations.name as organization_name')
                         ->orderBy('trainings.start_date', 'desc')
                         ->paginate(10);
                         
        // Get statistics for the teacher's trainings
        $stats = [
            'total' => $query->count(),
            'completed' => $query->clone()->where('trainings.status', 'completed')->count(),
            'ongoing' => $query->clone()->where('trainings.status', 'verified')
                              ->where('trainings.start_date', '<=', now())
                              ->where('trainings.end_date', '>=', now())
                              ->count(),
            'upcoming' => $query->clone()->where('trainings.status', 'verified')
                              ->where('trainings.start_date', '>', now())
                              ->count()
        ];
                         
        return view('teacher.training.index', compact('trainings', 'stats'));
    }

    /**
     * Approve a training request.
     */
    public function approve($id)
    {
        try {
            $training = Training::findOrFail($id);
            
            // Check if the training is pending
            if ($training->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending trainings can be approved'
                ], 400);
            }
            
            $training->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

            // Log training approval
            Log::channel('training')->info('Training Approved', [
                'user_id' => Auth::id(),
                'training_id' => $id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Training approved successfully'
            ]);
        } catch (\Exception $e) {
            // Log any errors during training approval
            Log::channel('training')->error('Training Approval Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error approving training: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a training request or invitation.
     */
    public function reject(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            // Validate rejection reason
            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                throw new \Exception('Invalid rejection reason: ' . $validator->errors()->first());
            }

            // Find the teacher profile
            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->firstOrFail();

            // Find the training
            $training = Training::findOrFail($id);

            // Find the training teacher record
            $trainingTeacher = TrainingTeacher::where('training_id', $id)
                ->where('teacher_id', $teacherProfile->teacher_id)
                ->whereIn('status', ['pending', 'invited', 'active'])
                ->firstOrFail();

            // Update training teacher status
            $trainingTeacher->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $request->reason
            ]);

            // Log training rejection
            Log::channel('training')->info('Training Invitation Rejected', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'teacher_id' => $teacherProfile->teacher_id,
                'rejection_reason' => $request->reason
            ]);

            // Commit the transaction
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Training invitation rejected successfully',
                'status' => $trainingTeacher->status
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction
            \DB::rollBack();

            // Log any errors during training rejection
            Log::channel('training')->error('Training Rejection Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject training: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show training details.
     */
    public function show($id)
    {
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();
        
        if (!$teacherProfile) {
            return redirect()->route('teacher.profile.setup')
                           ->with('warning', 'Please complete your profile first.');
        }
        
        $training = Training::with(['organization'])
                          ->join('training_teachers', 'trainings.training_id', '=', 'training_teachers.training_id')
                          ->where('training_teachers.teacher_id', $teacherProfile->teacher_id)
                          ->where('training_teachers.status', 'active')
                          ->where('trainings.training_id', $id)
                          ->firstOrFail();
                          
        return view('teacher.training.show', compact('training'));
    }

    /**
     * Confirm attendance for a training.
     */
    public function confirmAttendance($id)
    {
        try {
            // Log the start of attendance confirmation with more details
            Log::channel('training')->info('Attendance Confirmation Initiated', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'request_method' => request()->method(),
                'request_url' => request()->fullUrl()
            ]);

            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->firstOrFail();
            
            // Find the specific training teacher record
            $trainingTeacher = TrainingTeacher::where('training_id', $id)
                                            ->where('teacher_id', $teacherProfile->teacher_id)
                                            ->firstOrFail();

            // Update training teacher status
            $trainingTeacher->update([
                'attendance_confirmed' => true,
                'attendance_confirmed_at' => now()
            ]);

            // Log successful attendance confirmation
            Log::channel('training')->info('Attendance Confirmed Successfully', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'teacher_id' => $teacherProfile->teacher_id,
                'previous_attendance_status' => $trainingTeacher->getOriginal('attendance_confirmed')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance confirmed successfully'
            ]);
        } catch (\Exception $e) {
            // Log any errors during attendance confirmation
            Log::channel('training')->error('Attendance Confirmation Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error confirming attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload training report.
     */
    public function uploadReport(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            // Validate report upload
            $validator = Validator::make($request->all(), [
                'report' => 'file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'report_file' => 'file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'report_remarks' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                throw new \Exception('Invalid report upload: ' . $validator->errors()->first());
            }

            // Find the teacher profile
            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->firstOrFail();

            // Find the training teacher record
            $trainingTeacher = TrainingTeacher::where('training_id', $id)
                ->where('teacher_id', $teacherProfile->teacher_id)
                ->whereIn('status', ['accepted', 'attended', 'partially_attended'])
                ->firstOrFail();

            // Determine which file input was used
            $file = $request->file('report') ?? $request->file('report_file');
            
            if (!$file) {
                throw new \Exception('No file uploaded');
            }

            // Handle file upload
            $reportPath = $file->store('training_reports', 'public');

            // Determine remarks
            $remarks = $request->input('report_remarks') ?? '';

            // Update training teacher report details
            $trainingTeacher->update([
                'report_path' => $reportPath,
                'report_remarks' => $remarks,
                'report_submitted_at' => now(),
                'report_approved' => null // Pending approval
            ]);

            // Log report upload
            Log::channel('training')->info('Training Report Uploaded', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'teacher_id' => $teacherProfile->teacher_id,
                'report_path' => $reportPath,
                'file_details' => [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ]
            ]);

            // Commit the transaction
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Training report uploaded successfully',
                'report_path' => $reportPath
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction
            \DB::rollBack();

            // Log any errors during report upload
            Log::channel('training')->error('Training Report Upload Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload training report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept a training invitation.
     *
     * @param int $id Training ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept($id)
    {
        \DB::beginTransaction();
        try {
            // Log the start of training acceptance
            Log::channel('training')->info('Training Acceptance Initiated', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'request_method' => request()->method(),
                'request_url' => request()->fullUrl()
            ]);

            // Find or create the teacher profile
            $teacherProfile = TeacherProfile::firstOrCreate(
                ['user_id' => Auth::id()],
                [
                    'teacher_id' => (string) Str::uuid(),
                    'registration_number' => 'AUTO_' . Str::random(6),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // Find the training
            $training = Training::findOrFail($id);

            // Check training status
            if (!in_array($training->status, ['verified', 'pending', 'active'])) {
                throw new \Exception('Training is not in an acceptable state.');
            }

            // Find or create the training teacher record
            $trainingTeacher = TrainingTeacher::firstOrCreate(
                [
                'training_id' => $id,
                'teacher_id' => $teacherProfile->teacher_id
                ],
                [
                    'status' => 'pending',
                    'invited_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );

            // Validate current status
            if (!in_array($trainingTeacher->status, ['pending', 'invited'])) {
                throw new \Exception('Training invitation cannot be accepted at this time.');
            }

            // Update training teacher status
            $trainingTeacher->update([
                'status' => 'accepted',
                'accepted_at' => now(),
                'invitation_remarks' => 'Accepted by teacher'
            ]);

            // Commit the transaction
            \DB::commit();

            // Log successful training acceptance
            Log::channel('training')->info('Training Accepted Successfully', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'teacher_id' => $teacherProfile->teacher_id,
                'previous_status' => $trainingTeacher->getOriginal('status')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Training accepted successfully',
                'status' => $trainingTeacher->status
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction
            \DB::rollBack();

            // Log any errors during training acceptance
            Log::channel('training')->error('Training Acceptance Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to accept training: ' . $e->getMessage()
            ], 500);
        }
    }
}
