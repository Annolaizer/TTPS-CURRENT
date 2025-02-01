<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\TrainingTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            
            return response()->json([
                'success' => true,
                'message' => 'Training approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error approving training: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a training request.
     */
    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:500'
            ]);
            
            $training = Training::findOrFail($id);
            
            // Check if the training is pending
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
            
            return response()->json([
                'success' => true,
                'message' => 'Training rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting training: ' . $e->getMessage()
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
}
