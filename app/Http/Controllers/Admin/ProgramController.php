<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProgramController extends Controller
{
    public function index($subject_id)
    {
        try {
            $subject = Subject::findOrFail($subject_id);
            $programs = $subject->programs()->orderBy('program_name')->get();
            
            return response()->json([
                'error' => false,
                'data' => [
                    'subject' => $subject,
                    'programs' => $programs
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch programs: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch programs. Please try again.'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'program_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'subject_id' => 'required|exists:subjects,subject_id'
            ]);

            DB::beginTransaction();
            
            $program = Program::create($validated);
            
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Program created successfully',
                'data' => $program
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create program: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to create program. Please try again.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'program_name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            DB::beginTransaction();
            
            $program = Program::findOrFail($id);
            $program->update($validated);
            
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Program updated successfully',
                'data' => $program
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update program: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to update program. Please try again.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $program = Program::findOrFail($id);
            $program->delete();
            
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Program deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete program: ' . $e->getMessage());
            
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete program. Please try again.'
            ], 500);
        }
    }
}
