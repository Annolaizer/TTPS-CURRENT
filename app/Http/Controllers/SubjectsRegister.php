<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubjectsRegister extends Controller
{
    public function index(Request $request)
    {
        try {
            $subjects = Subject::orderBy('subject_name')->get();
            return view('admin.subjects.index', ['subjects' => $subjects]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch subjects: ' . $e->getMessage());
            return back()->with('error', 'Failed to load subjects. Please try again.');
        }
    }

    public function all()
    {
        try {
            $subjects = Subject::orderBy('subject_name')->get();
            return response()->json([
                'error' => false,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch subjects: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch subjects. Please try again.'
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_name' => 'required|string|max:255|unique:subjects,subject_name'
            ]);

            DB::beginTransaction();
            
            $subject = Subject::create($validated);
            
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Subject created successfully',
                'data' => $subject
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create subject: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to create subject. Please try again.'
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:subjects,subject_id'
            ]);

            DB::beginTransaction();
            
            $subject = Subject::findOrFail($validated['id']);
            $subject->delete();
            
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Subject deleted successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete subject: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete subject. Please try again.'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:subjects,subject_id',
                'subject_name' => 'required|string|max:255|unique:subjects,subject_name,' . $request->id . ',subject_id'
            ]);

            DB::beginTransaction();
            
            $subject = Subject::findOrFail($validated['id']);
            $subject->update(['subject_name' => $validated['subject_name']]);
            
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Subject updated successfully',
                'data' => $subject
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update subject: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to update subject. Please try again.'
            ], 500);
        }
    }
}
