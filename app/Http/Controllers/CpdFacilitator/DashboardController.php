<?php

namespace App\Http\Controllers\CpdFacilitator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('cpd_facilitator.dashboard.index');
    }

    public function profile()
    {
        return view('cpd_facilitator.dashboard.profile');
    }

    public function profileSetup()
    {
        return view('cpd_facilitator.dashboard.profile-setup');
    }
}
