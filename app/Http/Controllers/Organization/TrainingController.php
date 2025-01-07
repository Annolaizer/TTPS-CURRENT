<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Training;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TrainingController extends Controller
{
    protected $perPageOptions = [10, 25, 100];

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get organization directly using user_id
        $organization = Organization::where('user_id', $user->user_id)->first();

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
                'perPageOptions' => $this->perPageOptions
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
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('training_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->has('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
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
            'perPageOptions' => $this->perPageOptions
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
}
