<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;

class SubjectsRegister extends Controller
{
    public function index(Request $request){
        try{
           $subjects = Subject::all();

           Log::info('available Subjects'.$subjects);

        return view('admin.subjects.index', ['subjects' => $subjects]);

        }catch(\Exception $e){
            Log::info('An Error Occured'.$e->getMessage());
            return response()->json([
                'error'=>$e->getMessage(),
                'message'=>'failed to obtain Subjects Data'
            ], 404);
        }
    }

    public function create(Request $request){
        try{
            // Validate the request
            $request->validate([
                'subject_name' => 'required|string|max:255',
            ]);
    
            // Get subject name from request
            $subject_name = $request->input('subject_name');
    
            // Create the new subject
            Subject::create([
                'subject_name' => $subject_name,
            ]);
    
            // Return success response
            return response()->json([
                'error' => false,
                'message' => 'Subject added successfully',
            ], 200);

        }catch(\Exception $e){
            Log::info('An Error Occured'.$e->getMessage());
            return response()->json([
                'error'=>$e->getMessage(),
                'message'=>'failed to create Subject'
            ], 404);
        }
    }

    public function delete(Request $request){
        try{
            // Validate the request
            $request->validate([
                'subject_id' => 'required|integer',
            ]);
    
            // Get subject ID from request
            $subject_id = $request->input('subject_id');
    
            // Find the subject
            $subject = Subject::find($subject_id);
    
            // Check if the subject exists
            if (!$subject) {
                return response()->json([
                    'error' => true,
                    'message' => 'Subject not found',
                ], 404);
            }
    
            // Delete the subject
            $subject->delete();
    
            // Return success response
            return response()->json([
                'error' => false,
                'message' => 'Subject deleted successfully',
            ], 200);

        }catch(\Exception $e){
            Log::info('An Error Occured'.$e->getMessage());
            return response()->json([
                'error'=>$e->getMessage(),
                'message'=>'failed to delete Subject'
            ], 404);
        }
    }

    public function update(Request $request){
        try{
            // Validate the request
            $request->validate([
                'subject_id' => 'required|integer',
                'subject_name' => 'required|string|max:255',
            ]);
    
            // Get subject ID and name from request
            $subject_id = $request->input('subject_id');
            $subject_name = $request->input('subject_name');
    
            // Find the subject
            $subject = Subject::find($subject_id);
    
            // Check if the subject exists
            if (!$subject) {
                return response()->json([
                    'error' => true,
                    'message' => 'Subject not found',
                ], 404);
            }
    
            // Update the subject
            $subject->subject_name = $subject_name;
            $subject->save();
    
            // Return success response
            return response()->json([
                'error' => false,
                'message' => 'Subject updated successfully',
            ], 200);

        }catch(\Exception $e){
            Log::info('An Error Occured'.$e->getMessage());
            return response()->json([
                'error'=>$e->getMessage(),
                'message'=>'failed to update Subject'
            ], 404);
        }
    }
}
