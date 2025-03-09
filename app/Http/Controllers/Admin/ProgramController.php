<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProgramController extends Controller 
{
    /**
     * Display the programs listing page
     */
    public function index() 
    {
        return view('admin.programs.index');
    }

    /**
     * Get programs data for DataTables
     */
    public function getData(Request $request) 
    {
        $query = Program::with('subject');
        
        if ($request->has('search_filter') && !empty($request->search_filter)) {
            $searchTerm = $request->search_filter;
            $query->where('program_name', 'like', "%{$searchTerm}%");
        }
        
        if ($request->has('subject_filter') && !empty($request->subject_filter)) {
            $query->where('subject_id', $request->subject_filter);
        }
        
        return DataTables::of($query)->toJson();
    }

    /**
     * Store a newly created program
     */
    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'program_name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,subject_id',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            Program::create($request->all());
            return response()->json([
                'error' => false,
                'message' => 'Program created successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create program.'
            ], 500);
        }
    }

    /**
     * Store multiple newly created programs
     */
    public function bulkStore(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:subjects,subject_id',
            'programs' => 'required|array',
            'programs.*' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();
            foreach ($request->programs as $programName) {
                Program::create([
                    'program_name' => trim($programName),
                    'subject_id' => $request->subject_id
                ]);
            }
            DB::commit();
            return response()->json([
                'error' => false,
                'message' => count($request->programs) . ' programs created successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to create programs.'
            ], 500);
        }
    }

    /**
     * Get a specific program
     */
    public function show($id) 
    {
        try {
            $program = Program::findOrFail($id);
            return response()->json([
                'error' => false,
                'data' => $program
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Program not found.'
            ], 404);
        }
    }

    /**
     * Update a program
     */
    public function update(Request $request, $id) 
    {
        $validator = Validator::make($request->all(), [
            'program_name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,subject_id',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $program = Program::findOrFail($id);
            $program->update($request->all());
            return response()->json([
                'error' => false,
                'message' => 'Program updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update program.'
            ], 500);
        }
    }

    /**
     * Delete a program
     */
    public function destroy($id) 
    {
        try {
            DB::beginTransaction();
            
            // Handle both single and bulk deletes
            $ids = request()->input('ids', [$id]);
            
            // Validate that all IDs exist
            $validator = Validator::make(['ids' => $ids], [
                'ids' => 'required|array',
                'ids.*' => 'required|exists:programs,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            Program::whereIn('id', $ids)->delete();
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => count($ids) . ' program(s) deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete program(s).'
            ], 500);
        }
    }
}
