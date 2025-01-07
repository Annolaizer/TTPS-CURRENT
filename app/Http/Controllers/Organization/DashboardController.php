<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();
        
        return view('organization.dashboard.index', [
            'user' => $user,
            'organization' => $organization
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();
        
        return view('organization.profile.index', [
            'user' => $user,
            'organization' => $organization
        ]);
    }

    public function profileSetup()
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->user_id)->first();
        
        return view('organization.profile.setup', [
            'user' => $user,
            'organization' => $organization
        ]);
    }
}
