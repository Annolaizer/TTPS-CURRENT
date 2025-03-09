<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        $subjects = Subject::with('programs')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'subject_name' => 'required|string|max:255|unique:subjects,subject_name',
                'description' => 'nullable|string'
            ]);

            Subject::create($request->all());

            return response()->json(['message' => 'Subject created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating subject: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating subject'], 500);
        }
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        try {
            $request->validate([
                'subject_name' => 'required|string|max:255|unique:subjects,subject_name,' . $subject->subject_id . ',subject_id',
                'description' => 'nullable|string'
            ]);

            $subject->update($request->all());

            return response()->json(['message' => 'Subject updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating subject'], 500);
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            return response()->json(['message' => 'Subject deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting subject'], 500);
        }
    }
}
