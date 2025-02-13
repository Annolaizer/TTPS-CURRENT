<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeacherProfile;
use App\Models\PersonalInfo;
use App\Models\Ward;
use App\Models\District;
use App\Models\Region;
use Illuminate\Support\Facades\DB;

class TeacherProfileController extends Controller
{
    public function index()
    {
        return view('teacher.basic_info.index_new');
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone_number' => 'required|string|max:20',
            'registration_number' => 'required|string|max:50|unique:teacher_profiles,registration_number,' . auth()->user()->teacherProfile?->teacher_id . ',teacher_id',
            'education_level' => 'required|string|max:100',
            'teaching_subjects' => 'required|array|min:1',
            'teaching_subjects.*' => 'required|string|max:100',
            'years_of_experience' => 'required|integer|min:0|max:50',
            'school_name' => 'required|string|max:255',
            'ward_id' => 'required|exists:wards,ward_id'
        ]);

        try {
            DB::beginTransaction();

            // Update personal info
            PersonalInfo::updateOrCreate(
                ['user_id' => auth()->user()->user_id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_number' => $request->phone_number
                ]
            );

            // Update teacher profile
            TeacherProfile::updateOrCreate(
                ['user_id' => auth()->user()->user_id],
                [
                    'registration_number' => $request->registration_number,
                    'education_level' => $request->education_level,
                    'teaching_subjects' => $request->teaching_subjects,
                    'years_of_experience' => $request->years_of_experience,
                    'school_name' => $request->school_name,
                    'ward_id' => $request->ward_id,
                    'status' => 'pending'
                ]
            );

            DB::commit();

            return redirect()->route('teacher.dashboard')
                ->with('success', 'Profile updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }
}
