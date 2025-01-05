<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Organization;
use App\Models\Region;
use App\Models\District;
use App\Models\Ward;
use App\Models\Subject;
use App\Services\TrainingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    protected $trainingService;

    public function __construct(TrainingService $trainingService)
    {
        $this->trainingService = $trainingService;
    }

    /**
     * Display training listing page
     */
    public function index()
    {
        $organizations = Organization::where('status', 'active')->get();
        $subjects = Subject::orderBy('subject_name')->get();
        $regions = Region::orderBy('region_name')->get();
        
        return view('Trainings.index', compact('organizations', 'subjects', 'regions'));
    }

    /**
     * Get filtered trainings for DataTable
     */
    public function getTrainings(Request $request)
    {
        $filters = $request->only([
            'search',
            'status',
            'organization_id',
            'start_date',
            'end_date',
            'ownership'
        ]);

        $trainings = $this->trainingService->getFilteredTrainings($filters);

        return response()->json([
            'data' => $trainings->items(),
            'recordsTotal' => $trainings->total(),
            'recordsFiltered' => $trainings->total(),
        ]);
    }

    /**
     * Store a new training
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,organization_id',
            'description' => 'required|string',
            'education_level' => 'required|in:Primary,Lower Secondary,Higher Secondary',
            'training_phase' => 'required|integer|between:1,4',
            'duration_days' => 'required|integer|min:1',
            'max_participants' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'ward_id' => 'required|exists:wards,ward_id',
            'venue_name' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,subject_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $training = $this->trainingService->createTraining([
                ...$request->except('subjects'),
                'status' => 'draft',
                'location' => [
                    'ward_id' => $request->ward_id,
                    'venue_name' => $request->venue_name
                ],
                'subjects' => $request->subjects
            ]);

            return response()->json([
                'message' => 'Training created successfully',
                'training' => $training
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating training'], 500);
        }
    }

    /**
     * Update training
     */
    public function update(Request $request, Training $training)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,organization_id',
            'description' => 'required|string',
            'education_level' => 'required|in:Primary,Lower Secondary,Higher Secondary',
            'training_phase' => 'required|integer|between:1,4',
            'duration_days' => 'required|integer|min:1',
            'max_participants' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'ward_id' => 'required|exists:wards,ward_id',
            'venue_name' => 'required|string|max:255',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,subject_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $training = $this->trainingService->updateTraining($training, [
                ...$request->except('subjects'),
                'location' => [
                    'ward_id' => $request->ward_id,
                    'venue_name' => $request->venue_name
                ],
                'subjects' => $request->subjects
            ]);

            return response()->json([
                'message' => 'Training updated successfully',
                'training' => $training
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating training'], 500);
        }
    }

    /**
     * Get training details
     */
    public function show(Training $training)
    {
        $training->load(['organization', 'location.ward.district.region', 'subjects']);
        return response()->json($training);
    }

    /**
     * Delete training
     */
    public function destroy(Training $training)
    {
        try {
            DB::beginTransaction();
            
            // Delete related records first
            $training->location()->delete();
            $training->subjects()->detach();
            $training->delete();
            
            DB::commit();
            return response()->json(['message' => 'Training deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error deleting training'], 500);
        }
    }

    /**
     * Change training status
     */
    public function changeStatus(Request $request, Training $training)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,pending,verified,rejected,ongoing,completed',
            'rejection_reason' => 'required_if:status,rejected|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $training = $this->trainingService->changeStatus(
                $training, 
                $request->status,
                $request->rejection_reason
            );

            return response()->json([
                'message' => 'Training status updated successfully',
                'training' => $training
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating training status'], 500);
        }
    }

    /**
     * Get districts for a region
     */
    public function getDistricts(Region $region)
    {
        $districts = $region->districts()->orderBy('district_name')->get();
        return response()->json($districts);
    }

    /**
     * Get wards for a district
     */
    public function getWards(District $district)
    {
        $wards = $district->wards()->orderBy('ward_name')->get();
        return response()->json($wards);
    }
}
