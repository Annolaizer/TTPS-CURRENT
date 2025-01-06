<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Organization;
use App\Models\Region;
use App\Models\Subject;
use App\Models\Training;
use App\Models\Ward;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::with(['organization'])->get();
        
        return view('Trainings.index', [
            'organizations' => Organization::all(['organization_id', 'name']),
            'regions' => Region::all(['region_id', 'region_name']),
            'subjects' => Subject::all(['subject_id', 'subject_name']),
            'trainings' => $trainings
        ])->with('trainingsJson', json_encode($trainings));
    }

    /**
     * Get a specific training's details
     *
     * @param string $trainingCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($trainingCode)
    {
        try {
            $training = Training::with(['organization', 'subjects'])
                ->where('training_code', $trainingCode)
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'training' => $training
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Training not found'
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch training: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load training details'
            ], 500);
        }
    }

    /**
     * Get districts for a specific region
     *
     * @param int $regionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistricts($regionId)
    {
        try {
            $districts = District::where('region_id', $regionId)
                ->orderBy('district_name')
                ->get(['district_id', 'district_name']);

            return response()->json([
                'status' => 'success',
                'districts' => $districts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load districts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wards for a specific district
     *
     * @param int $districtId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWards($districtId)
    {
        try {
            $wards = Ward::where('district_id', $districtId)
                ->orderBy('ward_name')
                ->get(['ward_id', 'ward_name']);

            return response()->json([
                'status' => 'success',
                'wards' => $wards
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load wards: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get trainings data for DataTables
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainings(Request $request)
    {
        try {
            $query = Training::query()
                ->with(['organization']);

            // Apply search filter
            if ($request->has('search_filter') && !empty($request->search_filter)) {
                $searchTerm = $request->search_filter;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('training_code', 'like', "%{$searchTerm}%");
                });
            }

            // Apply date filter
            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->whereDate('created_at', $request->start_date);
            }

            // Apply organization filter
            if ($request->has('organization_id') && !empty($request->organization_id)) {
                $query->where('organization_id', $request->organization_id);
            }

            // Apply ownership filter through organization relationship
            if ($request->has('ownership') && !empty($request->ownership)) {
                $query->whereHas('organization', function($q) use ($request) {
                    $q->where('type', $request->ownership);
                });
            }

            // Apply status filter
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addColumn('organization', function ($training) {
                    return $training->organization ? $training->organization->name : '';
                })
                ->editColumn('status', function ($training) {
                    $statusClasses = [
                        'pending' => 'bg-warning',
                        'verified' => 'bg-success',
                        'rejected' => 'bg-danger',
                        'ongoing' => 'bg-info',
                        'completed' => 'bg-primary'
                    ];
                    $class = $statusClasses[$training->status] ?? 'bg-secondary';
                    return '<span class="badge ' . $class . '">' . ucfirst($training->status) . '</span>';
                })
                ->filterColumn('organization', function($query, $keyword) {
                    $query->whereHas('organization', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['status'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Error fetching trainings: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to fetch trainings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new training
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'organization_id' => 'required|exists:organizations,organization_id',
                'education_level' => 'required|string',
                'training_phase' => 'required|integer',
                'max_participants' => 'required|integer|min:1',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'required|date_format:H:i',
                'duration_days' => 'required|integer|min:1',
                'region_id' => 'required|exists:regions,region_id',
                'district_id' => 'required|exists:districts,district_id',
                'ward_id' => 'required|exists:wards,ward_id',
                'venue_name' => 'required|string|max:255',
                'subjects' => 'required|array',
                'subjects.*' => 'exists:subjects,subject_id'
            ]);

            // Generate training code
            $maxId = Training::max('training_id') ?? 0;
            $nextId = $maxId + 1;
            $trainingCode = 'TRN-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // Create training
            $training = Training::create([
                'training_code' => $trainingCode,
                'title' => $validated['title'],
                'organization_id' => $validated['organization_id'],
                'education_level' => $validated['education_level'],
                'training_phase' => $validated['training_phase'],
                'max_participants' => $validated['max_participants'],
                'description' => $validated['description'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'start_time' => $validated['start_time'],
                'duration_days' => $validated['duration_days'],
                'region_id' => $validated['region_id'],
                'district_id' => $validated['district_id'],
                'ward_id' => $validated['ward_id'],
                'venue_name' => $validated['venue_name'],
                'status' => 'pending'
            ]);

            // Attach subjects
            $training->subjects()->attach($validated['subjects']);

            // Load relationships for response
            $training->load(['organization', 'subjects']);

            return response()->json([
                'status' => 'success',
                'message' => 'Training created successfully',
                'training' => $training
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Training creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create training: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a training
     *
     * @param Request $request
     * @param string $trainingCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $trainingCode)
    {
        try {
            $training = Training::where('training_code', $trainingCode)->firstOrFail();

            // Parse raw input for multipart/form-data
            $rawInput = file_get_contents('php://input');
            $boundary = substr($rawInput, 0, strpos($rawInput, "\r\n"));
            $parts = array_slice(explode($boundary, $rawInput), 1, -1);
            $formData = [];

            foreach ($parts as $part) {
                if (empty($part)) continue;
                
                // Extract field name and value
                if (preg_match('/name=\"([^\"]*)\".*(?:\r\n|\n|\r){4}(.*)(?:\r\n|\n|\r)/U', $part, $matches)) {
                    $name = $matches[1];
                    $value = trim($matches[2]);
                    
                    // Handle array fields like subjects[]
                    if (strpos($name, '[') !== false) {
                        $name = str_replace(['[', ']'], ['.', ''], $name);
                        Arr::set($formData, $name, $value);
                    } else {
                        $formData[$name] = $value;
                    }
                }
            }

            // Log parsed form data
            \Log::info('Training Update Form Data:', $formData);

            // Validate the data
            $validated = Validator::make($formData, [
                'title' => 'required|string|max:255',
                'organization_id' => 'required|exists:organizations,organization_id',
                'education_level' => 'required|string',
                'training_phase' => 'required|integer',
                'max_participants' => 'required|integer',
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'required',
                'duration_days' => 'required|integer',
                'region_id' => 'required|exists:regions,region_id',
                'district_id' => 'required|exists:districts,district_id',
                'ward_id' => 'required|exists:wards,ward_id',
                'venue_name' => 'required|string',
                'subjects' => 'required|array',
                'subjects.*' => 'exists:subjects,subject_id'
            ])->validate();

            // Update the training
            $training->update($validated);

            // Sync subjects
            if (isset($formData['subjects'])) {
                $subjects = is_array($formData['subjects']) ? $formData['subjects'] : [$formData['subjects']];
                $training->subjects()->sync($subjects);
            }

            return response()->json(['message' => 'Training updated successfully']);
        } catch (ValidationException $e) {
            \Log::error('Training validation failed:', [
                'errors' => $e->errors(),
                'data' => $formData ?? null
            ]);
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Training update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Failed to update training'], 500);
        }
    }

    /**
     * Delete a training
     *
     * @param string $trainingCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($trainingCode)
    {
        try {
            \Log::info('Starting deletion process for training code: ' . $trainingCode);
            
            // Find the training
            $training = Training::where('training_code', $trainingCode)->first();
            
            if (!$training) {
                \Log::warning('Training not found: ' . $trainingCode);
                return response()->json([
                    'success' => false,
                    'message' => 'Training not found.'
                ], 404);
            }

            \Log::info('Found training with ID: ' . $training->training_id);
            $trainingId = $training->training_id;

            // Start transaction
            DB::beginTransaction();

            try {
                // Use raw queries to bypass Eloquent and foreign key checks
                DB::unprepared('SET FOREIGN_KEY_CHECKS=0;');

                // Delete from known related tables
                $queries = [
                    "DELETE FROM training_locations WHERE training_id = {$trainingId}",
                    "DELETE FROM training_subjects WHERE training_id = {$trainingId}",
                    "DELETE FROM training_facilitators WHERE training_id = {$trainingId}",
                    "DELETE FROM trainings WHERE training_id = {$trainingId}"
                ];

                foreach ($queries as $query) {
                    try {
                        \Log::info('Executing query: ' . $query);
                        DB::unprepared($query);
                    } catch (\Exception $e) {
                        \Log::warning('Query failed but continuing: ' . $e->getMessage());
                        // Continue with other queries even if one fails
                        continue;
                    }
                }

                DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');
                DB::commit();

                \Log::info('Successfully deleted training and related records');

                return response()->json([
                    'success' => true,
                    'message' => 'Training deleted successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Failed to delete training: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete training: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify a training
     *
     * @param string $trainingCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($trainingCode)
    {
        try {
            \Log::info('Verifying training', ['training_code' => $trainingCode]);
            
            $training = Training::where('training_code', $trainingCode)->firstOrFail();
            \Log::info('Found training', ['training' => $training->toArray()]);

            if ($training->status !== 'pending') {
                \Log::warning('Training not in pending status', ['status' => $training->status]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only pending trainings can be verified'
                ], 400);
            }

            $training->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);
            \Log::info('Training verified successfully');

            return response()->json([
                'status' => 'success',
                'message' => 'Training has been verified successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Training not found', ['training_code' => $trainingCode]);
            return response()->json([
                'status' => 'error',
                'message' => 'Training not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Training verification failed: ' . $e->getMessage(), [
                'training_code' => $trainingCode,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to verify training: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a training
     *
     * @param Request $request
     * @param string $trainingCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, $trainingCode)
    {
        try {
            $training = Training::where('training_code', $trainingCode)->firstOrFail();

            if ($training->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only pending trainings can be rejected'
                ], 400);
            }

            $validated = $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $training->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['reason'],
                'rejected_at' => now(),
                'rejected_by' => auth()->id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Training has been rejected successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Training not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Training rejection failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject training: ' . $e->getMessage()
            ], 500);
        }
    }
}
