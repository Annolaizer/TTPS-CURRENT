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

        return json_encode($subjects);

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
           $subjects = Subject::all();

           Log::info('available Subjects'.$subjects);

        return response()->json([
            'error' => 'failed to add subject',
            'message' => 'System error'
        ],200);

        }catch(\Exception $e){
            Log::info('An Error Occured'.$e->getMessage());
            return response()->json([
                'error'=>$e->getMessage(),
                'message'=>'failed to obtain Subjects Data'
            ], 404);
        }
    }
}
