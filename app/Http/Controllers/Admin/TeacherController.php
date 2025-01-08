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

    public function show($teacherId)
    {
        $teacher = TeacherProfile::with(['user' => function($query) {
            $query->select('user_id', 'name', 'email', 'status');
        }, 'trainings'])->findOrFail($teacherId);

        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit($teacherId)
    {
        $teacher = TeacherProfile::with(['user' => function($query) {
            $query->select('user_id', 'name', 'email');
        }])->findOrFail($teacherId);

        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $teacherId)
    {
        $teacher = TeacherProfile::findOrFail($teacherId);
        
        $validated = $request->validate([
            'registration_number' => 'required|string',
            'education_level' => 'required|string',
            'teaching_subject' => 'required|string',
            'years_of_experience' => 'required|integer',
            'current_school' => 'required|string',
            'ward_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $teacher->update($validated);
            DB::commit();
            return redirect()->route('admin.teachers.show', $teacherId)
                ->with('success', 'Teacher information updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating teacher information. Please try again.');
        }
    }

    public function toggleStatus(Request $request, $teacherId)
    {
        $teacher = TeacherProfile::findOrFail($teacherId);
        $newStatus = $request->input('status');
        
        if (!in_array($newStatus, ['active', 'inactive'])) {
            return response()->json(['error' => 'Invalid status'], 422);
        }

        DB::beginTransaction();
        try {
            $teacher->update(['status' => $newStatus]);
            // Also update the user status
            $teacher->user()->update(['status' => $newStatus]);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error updating status'], 500);
        }
    }
}
