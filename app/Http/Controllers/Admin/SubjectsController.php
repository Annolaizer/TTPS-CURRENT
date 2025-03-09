<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the subjects.
     */
    public function index()
    {
        return view('admin.subjects.index');
    }

    /**
     * Get subjects data for DataTables
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        try {
            $query = Subject::query()
                ->with(['programs'])
                ->select(['subject_id', 'subject_name', 'description'])
                ->orderBy('subject_name', 'asc');

            // Apply search filter
            if ($request->has('search_filter') && !empty($request->search_filter)) {
                $searchTerm = $request->search_filter;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('subject_name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            return DataTables::of($query)
                ->addColumn('programs_count', function ($subject) {
                    return $subject->programs->count();
                })
                ->editColumn('subject_name', function ($subject) {
                    return $subject->subject_name;
                })
                ->rawColumns(['subject_name'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Error fetching subjects: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch subjects: ' . $e->getMessage()
            ], 500);
        }
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

            $subject = Subject::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific subject's details
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $subject = Subject::with(['programs'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'subject' => $subject
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching subject details'
            ], 500);
        }
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $subject = Subject::findOrFail($id);
            
            $request->validate([
                'subject_name' => 'required|string|max:255|unique:subjects,subject_name,' . $subject->subject_id . ',subject_id',
                'description' => 'nullable|string'
            ]);

            $subject->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all subjects (used for dropdowns)
     */
    public function all()
    {
        try {
            $subjects = Subject::select('subject_id', 'subject_name')
                ->orderBy('subject_name')
                ->get();

            return response()->json([
                'error' => false,
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching all subjects: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Error fetching subjects'
            ], 500);
        }
    }
}
