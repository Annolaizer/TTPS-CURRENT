<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'educationLevel', 'subjects'])->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function verifyAllCompleted()
    {
        try {
            DB::beginTransaction();

            // Get all teachers with completed profiles that are not yet verified
            $teachers = Teacher::whereHas('user', function($query) {
                $query->where('profile_completed', true)
                      ->where('is_verified', false);
            });

            $count = $teachers->count();
            
            // Update verification status
            $teachers->update([
                'is_verified' => true,
                'verified_at' => now()
            ]);

            // Update the users table as well
            DB::table('users')
                ->whereIn('id', $teachers->pluck('user_id'))
                ->update([
                    'is_verified' => true,
                    'verified_at' => now()
                ]);

            DB::commit();

            Log::info("Bulk verification completed", ['verified_count' => $count]);

            return response()->json([
                'success' => true,
                'verifiedCount' => $count,
                'message' => "Successfully verified {$count} teachers"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error during bulk verification", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify teachers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $currentStatus = $teacher->status;
            
            $teacher->update([
                'status' => $currentStatus === 'active' ? 'inactive' : 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'new_status' => $teacher->status
            ]);

        } catch (\Exception $e) {
            Log::error("Error toggling teacher status", [
                'teacher_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
}
