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
            ->where('training_teachers.status', 'active');
        
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
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                Log::channel('training')->warning('Training Rejection Validation Failed', [
                    'user_id' => Auth::id(),
                    'training_id' => $id,
                    'validation_errors' => $validator->errors()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->firstOrFail();
            
            // First try to find a pending training teacher record
            $trainingTeacher = TrainingTeacher::where('training_id', $id)
                                            ->where('teacher_id', $teacherProfile->teacher_id)
                                            ->where('status', 'pending')
                                            ->first();

            if ($trainingTeacher) {
                // Handle rejection of training invitation
                $trainingTeacher->update([
                    'status' => 'rejected',
                    'rejection_reason' => $request->reason,
                    'rejected_at' => now()
                ]);

                // Log training invitation rejection
                Log::channel('training')->info('Training Invitation Rejected', [
                    'user_id' => Auth::id(),
                    'training_id' => $id,
                    'rejection_reason' => $request->reason
                ]);
            } else {
                // Handle rejection of training request
                $training = Training::findOrFail($id);
                
                if ($training->status !== 'pending') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only pending trainings can be rejected'
                    ], 400);
                }
                
                $training->update([
                    'status' => 'rejected',
                    'rejection_reason' => $request->reason,
                    'rejected_at' => now(),
                    'rejected_by' => Auth::id()
                ]);

                // Log training request rejection
                Log::channel('training')->info('Training Request Rejected', [
                    'user_id' => Auth::id(),
                    'training_id' => $id,
                    'rejection_reason' => $request->reason
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Training rejected successfully'
            ]);
        } catch (\Exception $e) {
            // Log any errors during training rejection
            Log::channel('training')->error('Training Rejection Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'rejection_reason' => $request->input('reason')
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
        try {
            // Log the start of report upload with more details
            Log::channel('training')->info('Training Report Upload Initiated', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'request_data' => $request->except(['_token', 'report_file']),
                'request_method' => $request->method(),
                'request_url' => $request->fullUrl()
            ]);

            $validator = Validator::make($request->all(), [
                'report_file' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB max
                'remarks' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                Log::channel('training')->warning('Training Report Upload Validation Failed', [
                    'user_id' => Auth::id(),
                    'training_id' => $id,
                    'validation_errors' => $validator->errors()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $teacherProfile = TeacherProfile::where('user_id', Auth::id())->firstOrFail();
            
            // Find the specific training
            $trainingTeacher = TrainingTeacher::where('training_id', $id)
                                            ->where('teacher_id', $teacherProfile->teacher_id)
                                            ->firstOrFail();

            // Handle file upload
            if ($request->hasFile('report_file')) {
                $file = $request->file('report_file');
                $filename = 'training_report_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('training_reports', $filename, 'public');

                // Update training teacher record with report details
                $trainingTeacher->update([
                    'report_path' => $path,
                    'report_remarks' => $request->input('remarks', ''),
                    'report_submitted_at' => now()
                ]);

                // Log successful report upload
                Log::channel('training')->info('Training Report Uploaded Successfully', [
                    'user_id' => Auth::id(),
                    'training_id' => $id,
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'file_mime' => $file->getMimeType()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Report uploaded successfully',
                    'file_path' => $path
                ]);
            }

            throw new \Exception('No file uploaded');
        } catch (\Exception $e) {
            // Log any errors during report upload
            Log::channel('training')->error('Training Report Upload Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token', 'report_file'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept a training invitation.
     */
    public function accept($id)
    {
        \DB::beginTransaction();
        try {
            // Log the start of training acceptance with more details
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

            // Check if the training teacher can be accepted
            if (!$trainingTeacher->canAccept()) {
                throw new \Exception('Training cannot be accepted at this time.');
            }

            // Log the details of the training teacher record
            Log::channel('training')->info('Training Teacher Record Details', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'teacher_id' => $teacherProfile->teacher_id,
                'existing_status' => $trainingTeacher->status,
                'record_exists' => $trainingTeacher->wasRecentlyCreated ? 'Created' : 'Existing'
            ]);

            // Update training teacher status
            $trainingTeacher->update([
                'status' => 'active',
                'accepted_at' => now()
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
                'participation_status' => $trainingTeacher->participation_status
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction
            \DB::rollBack();

            // Log any errors during training acceptance
            Log::channel('training')->error('Training Acceptance Failed', [
                'user_id' => Auth::id(),
                'training_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_details' => Auth::user()->toArray()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to accept training: ' . $e->getMessage()
            ], 500);
        }
    }
}
