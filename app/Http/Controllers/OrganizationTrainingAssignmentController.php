<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\TeacherProfile;
use App\Models\Facilitator;
use App\Models\TrainingTeacher;
use App\Models\TrainingFacilitator;
use App\Models\Region;
use App\Models\Organization;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class OrganizationTrainingAssignmentController extends Controller
{
    public function index($trainingCode){
        $training = Training::with(['organization', 'subjects', 'ward.district.region'])
        ->where('training_code', $trainingCode)
        ->firstOrFail();

        return view('organization.trainings.assignment', [
            'training' => $training,
            'organizations' => Organization::all(['organization_id', 'name']),
            'regions' => Region::all(['region_id', 'region_name']),
            'subjects' => Subject::all(['subject_id', 'subject_name']),
            'user' => auth()->user()

        ]);
    }
}
