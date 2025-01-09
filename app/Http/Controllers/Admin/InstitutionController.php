<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstitutionController extends Controller
{
    public function index()
    {
        $institutions = Organization::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.institutions.index', compact('institutions'));
    }

    public function create()
    {
        return view('admin.institutions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Government,NGO,Private',
            'registration_number' => 'required|string|unique:organizations',
            'email' => 'required|email|unique:organizations',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $institution = Organization::create($validated + ['status' => 'active']);

        return redirect()
            ->route('admin.institutions.show', $institution->organization_id)
            ->with('success', 'Institution created successfully');
    }

    public function show($id)
    {
        try {
            $institution = Organization::findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'institution' => $institution->append('approval_letter_url')
                ]);
            }
            
            return view('admin.institutions.show', compact('institution'));
        } catch (\Exception $e) {
            Log::error('Error showing institution:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load institution details: ' . $e->getMessage()
                ], 500);
            }
            
            throw $e;
        }
    }

    public function edit($id)
    {
        $institution = Organization::findOrFail($id);
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'institution' => $institution
            ]);
        }
        return view('admin.institutions.edit', compact('institution'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $institution = Organization::findOrFail($id);
            
            // Log the request data for debugging
            Log::info('Institution update request:', [
                'id' => $id,
                'request_data' => $request->except('approval_letter'),
                'has_file' => $request->hasFile('approval_letter')
            ]);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:Government,NGO,Private',
                'registration_number' => 'required|string|unique:organizations,registration_number,' . $id . ',organization_id',
                'email' => 'required|email|unique:organizations,email,' . $id . ',organization_id',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string',
                'approval_letter' => 'nullable|file|mimes:pdf|max:10240' // 10MB max
            ]);

            // Log validated data
            Log::info('Validated data:', $validated);

            // Handle approval letter upload if present
            if ($request->hasFile('approval_letter')) {
                try {
                    $path = $institution->saveApprovalLetter($request->file('approval_letter'));
                    Log::info('Approval letter saved:', ['path' => $path]);
                } catch (\Exception $e) {
                    Log::error('Error saving approval letter:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }

            // Remove approval_letter from validated data as it's handled separately
            unset($validated['approval_letter']);
            
            // Update the institution
            $institution->update($validated);
            
            // Log the updated institution
            Log::info('Institution updated:', [
                'id' => $institution->organization_id,
                'data' => $institution->toArray()
            ]);
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Institution updated successfully',
                    'institution' => $institution->fresh()
                ]);
            }

            return redirect()
                ->route('admin.institutions.show', $institution->organization_id)
                ->with('success', 'Institution updated successfully');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error:', [
                'errors' => $e->errors(),
                'request_data' => $request->except('approval_letter')
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Institution update error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except('approval_letter')
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update institution: ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update institution: ' . $e->getMessage()]);
        }
    }

    public function deleteApprovalLetter($id)
    {
        $institution = Organization::findOrFail($id);
        
        if ($institution->deleteApprovalLetter()) {
            return response()->json([
                'success' => true,
                'message' => 'Approval letter deleted successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No approval letter found'
        ], 404);
    }

    public function toggleStatus(Request $request, $id)
    {
        $institution = Organization::findOrFail($id);
        $newStatus = $institution->status === 'active' ? 'inactive' : 'active';
        $institution->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Institution status updated successfully',
            'newStatus' => $newStatus
        ]);
    }
}
