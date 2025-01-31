<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Organization;
use App\Models\Subject;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainingController extends Controller
{
    protected $perPageOptions = [10, 25, 100];

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get organization directly using user_id
        $organization = Organization::where('user_id', $user->user_id)->first();

        // Get subjects and regions for the form
        $subjects = Subject::orderBy('subject_name')->get();
        $regions = Region::orderBy('region_name')->get();

        Log::info('User and Organization details', [
            'user_id' => $user->user_id,
            'organization' => $organization ? $organization->toArray() : null
        ]);

        if (!$organization) {
            Log::error('No organization found for user', ['user_id' => $user->user_id]);
            return view('organization.trainings.index', [
                'trainings' => collect([]),
                'search' => $request->search,
                'status' => $request->status,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'perPage' => 10,
                'perPageOptions' => $this->perPageOptions,
                'subjects' => $subjects,
                'regions' => $regions
            ]);
        }

        $query = Training::with(['region', 'district', 'ward'])
            ->where('organization_id', $organization->organization_id);

        Log::info('Training query results', [
            'count' => $query->count(),
            'organization_id' => $organization->organization_id,
            'first_training' => $query->first()
        ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('training_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }

        // Sort by latest by default
        $query->latest();

        // Get per page value from request or default to 10
        $perPage = in_array($request->per_page, $this->perPageOptions) ? $request->per_page : 10;

        $trainings = $query->paginate($perPage);

        Log::info('Final trainings result', [
            'count' => $trainings->count(),
            'total' => $trainings->total()
        ]);

        return view('organization.trainings.index', [
            'trainings' => $trainings,
            'search' => $request->search,
            'status' => $request->status,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
            'perPage' => $perPage,
            'perPageOptions' => $this->perPageOptions,
            'subjects' => $subjects,
            'regions' => $regions,
            'organization' => $organization
        ]);
    }

    public function show(Training $training)
    {
        $organization = Organization::where('user_id', Auth::user()->user_id)->first();
        
        if (!$organization || $training->organization_id !== $organization->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load the location relationships
        $training->load(['region', 'district', 'ward']);

        return view('organization.trainings.show', compact('training'));
    }

    public function store(Request $request)
    {
        Log::info($request->all());
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'organization_id' => 'required|exists:organizations,organization_id', // Ensure organization exists
                'education_level' => 'required|string',
                'training_phase' => 'required|integer', // Required integer
                'max_participants' => 'required|integer|min:1', // Required integer >= 1
                'description' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date', // End date must be after or equal to start date
                'start_time' => 'required|date_format:H:i', // Validate time format
                'duration_days' => 'required|integer|min:1', // Required integer >= 1
                'region_id' => 'required|exists:regions,region_id', // Ensure region exists
                'district_id' => 'required|exists:districts,district_id', // Ensure district exists
                'ward_id' => 'required|exists:wards,ward_id', // Ensure ward exists
                'venue_name' => 'required|string|max:255',
                'subjects' => 'required|array', // Must be an array
                'subjects.*' => 'exists:subjects,subject_id' // Each subject ID must exist
            ]);

            // Generate a unique training code
            $maxId = Training::max('training_id') ?? 0;
            $nextId = $maxId + 1;
            $trainingCode = 'TRN-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // Create the training record
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
                'status' => 'pending', // Default status
            ]);

            // Attach subjects to the training
            $training->subjects()->attach($validated['subjects']);

            // Load relationships for the response
            $training->load(['organization', 'subjects']);

            // Return a successful JSON response
            return response()->json([
                'status' => 'success',
                'message' => 'Training created successfully',
                'training' => $training
            ], 201);


        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Handle other exceptions
            \Log::error('Training creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create training. Please try again later.'
            ], 500);
        }
    }

    /**
     * Generate a comprehensive report for a specific training.
     *
     * @param Training $training
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Training $training)
    {
        // Eager load all necessary relationships
        $training->load([
            'organization',
            'teachers.user',
            'facilitators',
            'enrollments.teacher.user'
        ]);

        // Get assigned teachers (from training_teachers pivot table) with their users
        $assignedTeachers = $training->teachers;
        
        // Get enrolled teachers (from training_enrollments table) with their users
        $enrolledTeachers = $training->enrollments->map(function($enrollment) {
            return $enrollment->teacher;
        })->filter();
        
        // Get facilitators
        $facilitators = $training->facilitators;

        // Calculate teacher statistics
        $teacherStats = [
            'total_assigned' => $assignedTeachers->count(),
            'total_enrolled' => $enrolledTeachers->count(),
            'male_assigned' => $assignedTeachers->filter(function($teacher) {
                return optional($teacher->user)->gender === 'male';
            })->count(),
            'female_assigned' => $assignedTeachers->filter(function($teacher) {
                return optional($teacher->user)->gender === 'female';
            })->count(),
            'male_enrolled' => $enrolledTeachers->filter(function($teacher) {
                return optional($teacher->user)->gender === 'male';
            })->count(),
            'female_enrolled' => $enrolledTeachers->filter(function($teacher) {
                return optional($teacher->user)->gender === 'female';
            })->count(),
            'attended' => $training->enrollments->where('attendance_status', 'attended')->count(),
        ];
        
        // Calculate facilitator statistics
        $facilitatorStats = [
            'total' => $facilitators->count(),
            'male' => $facilitators->where('gender', 'male')->count(),
            'female' => $facilitators->where('gender', 'female')->count(),
            'attended' => $facilitators->whereIn('pivot.attendance_status', ['attended', 'present'])->count()
        ];

        // Calculate actual attendance rate
        $totalAssigned = $teacherStats['total_assigned'] + $facilitatorStats['total'];
        $totalAttended = $teacherStats['attended'] + $facilitatorStats['attended'];
        $attendanceRate = $totalAssigned > 0 ? ($totalAttended / $totalAssigned) * 100 : 0;

        // Generate PDF
        $pdf = Pdf::loadView('reports.training', [
            'training' => $training,
            'assignedTeachers' => $assignedTeachers,
            'enrolledTeachers' => $enrolledTeachers,
            'facilitators' => $facilitators,
            'teacherStats' => $teacherStats,
            'facilitatorStats' => $facilitatorStats,
            'attendanceRate' => $attendanceRate,
            'generatedAt' => now()
        ]);

        // Set paper size to A4
        $pdf->setPaper('A4');

        return $pdf->stream("training-report-{$training->training_code}.pdf");
    }
}
