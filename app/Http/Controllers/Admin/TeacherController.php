<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        // Eager load the user relationship and get all teachers
        $teachers = TeacherProfile::with(['user' => function($query) {
            $query->select('user_id', 'name', 'email', 'status');
        }])->get();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function show($user_id)
    {
        $teacher = TeacherProfile::with(['user' => function($query) {
            $query->select('user_id', 'name', 'email', 'status');
        }, 'trainings'])->where('user_id', $user_id)->firstOrFail();

        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit($user_id)
    {
        $teacher = TeacherProfile::with(['user' => function($query) {
            $query->select('user_id', 'name', 'email');
        }])->where('user_id', $user_id)->firstOrFail();

        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $user_id)
    {
        $teacher = TeacherProfile::where('user_id', $user_id)->firstOrFail();
        
        $validated = $request->validate([
            'registration_number' => 'required|string',
            'education_level' => 'required|string',
            'teaching_subject' => 'required|string',
            'years_of_experience' => 'required|integer',
            'current_school' => 'required|string',
            'ward_id' => 'required'
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.show', $user_id)
            ->with('success', 'Teacher profile updated successfully.');
    }

    public function toggleStatus(Request $request, $user_id)
    {
        try {
            $teacher = TeacherProfile::where('user_id', $user_id)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found'
                ], 404);
            }

            $newStatus = $request->input('status');
            
            if (!in_array($newStatus, ['active', 'inactive', 'verified'])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid status provided'
                ], 422);
            }

            // If verifying, check if profile is complete
            if ($newStatus === 'verified') {
                $isComplete = $teacher->registration_number &&
                             $teacher->education_level &&
                             $teacher->teaching_subject &&
                             $teacher->years_of_experience &&
                             $teacher->current_school &&
                             $teacher->ward_id;

                if (!$isComplete) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot verify teacher with incomplete profile. Please ensure all required information is provided.'
                    ], 422);
                }
            }

            DB::beginTransaction();
            try {
                $teacher->update(['status' => $newStatus]);
                // Also update the user status if not verifying
                if ($newStatus !== 'verified') {
                    $teacher->user()->update(['status' => $newStatus]);
                }
                DB::commit();
                return response()->json([
                    'success' => true, 
                    'message' => 'Teacher status has been updated successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'success' => false, 
                    'message' => 'Unable to update teacher status. Please try again.'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to process your request. Please try again.'
            ], 500);
        }
    }

    public function verifyCompleted()
    {
        DB::beginTransaction();
        try {
            $completedTeachers = TeacherProfile::whereNotNull('registration_number')
                ->whereNotNull('education_level')
                ->whereNotNull('teaching_subject')
                ->whereNotNull('years_of_experience')
                ->whereNotNull('current_school')
                ->whereNotNull('ward_id')
                ->where('status', '!=', 'verified')
                ->get();

            $count = 0;
            foreach ($completedTeachers as $teacher) {
                $teacher->update(['status' => 'verified']);
                $count++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $count . ' teachers verified successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify teachers'
            ], 500);
        }
    }
}
