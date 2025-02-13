<?php

namespace App\Http\Controllers;

use App\Models\PersonalInfo;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherProfileController extends Controller
{

    public function update(Request $request)
    {
        try {
            // Check if profile is already completed
            $teacherProfile = TeacherProfile::where('user_id', auth()->user()->user_id)->first();
            if ($teacherProfile && $teacherProfile->status === 'completed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your teacher profile is already completed and cannot be modified.'
                ], 403);
            }

            $user_id = optional(auth()->user())->user_id;
            
            if (!$user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Update TeacherProfile
            // Update TeacherProfile with completed status
            TeacherProfile::where('user_id', $user_id)->update([
                'status' => 'completed',
                'education_level' => $request->education_level,
                'teaching_subject' => json_encode($request->teaching_subjects),
                'years_of_experience' => $request->years_of_experience,
                'current_school' => $request->school_name,
                'ward_id' => $request->ward_id,
                'registration_number' => $request->registration_number
            ]);

            // Update or Create PersonalInfo using updateOrCreate
            PersonalInfo::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'title' => $request->title ?? 'Mr/Ms',  // Providing a default title
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                    'phone_number' => $request->phone_number,
                    'disability_status' => (bool)$request->disability_status,
                    'disability_type' => $request->disability_type
                ]
            );

            // Update User's name
            User::where('user_id', $user_id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile'
            ], 500);
        }
    }
    
    public function index()
    {
        $user = auth()->user();
        $teacherProfile = TeacherProfile::where('user_id', $user->user_id)->first();

        // Check if profile is already completed
        if ($teacherProfile && $teacherProfile->status === 'completed') {
            return redirect()->route('teacher.dashboard')->with('info', 'Your teacher profile is already completed.');
        }

        return view('teacher.basic_info.index');
    }

    public function store(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            // Personal Info Validation
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone_number' => 'required|regex:/^[0-9]{10,20}$/',
            'disability_status' => 'boolean',
            'disability_type' => 'required_if:disability_status,1',

            // Professional Info Validation
            'registration_number' => 'required|regex:/^[A-Za-z0-9]+$/',
            'education_level' => 'required|in:Certificate,Diploma,Bachelor\'s Degree,Master\'s Degree,PhD',
            'teaching_subjects' => 'required|array|min:1',
            'teaching_subjects.*' => 'required|string',
            'years_of_experience' => 'required|integer|min:0',

            // Location Info Validation
            'ward_id' => 'required|exists:wards,id',
            'school_name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Update user's information
            $user = auth()->user();
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'middle_name' => $validated['middle_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'title' => 'Mr/Ms', // Default title
            ]);

            // Update personal information
            PersonalInfo::updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'gender' => $validated['gender'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'phone_number' => $validated['phone_number'],
                    'disability_status' => $validated['disability_status'] ?? false,
                    'disability_type' => $validated['disability_type'] ?? null
                ]
            );

            // Update teacher profile
            TeacherProfile::updateOrCreate(
                ['user_id' => $user->user_id],
                [
                    'registration_number' => $validated['registration_number'],
                    'education_level' => $validated['education_level'],
                    'teaching_subjects' => $validated['teaching_subjects'],
                    'years_of_experience' => $validated['years_of_experience'],
                    'school_name' => $validated['school_name'],
                    'ward_id' => $validated['ward_id'],
                    'status' => 'pending_approval'
                ]
            );

            DB::commit();

            // Clear session storage after successful submission
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully!',
                'redirect' => route('teacher.dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating teacher profile: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile. Please try again.',
                'errors' => [$e->getMessage()]
            ], 422);
        }
    }
}
