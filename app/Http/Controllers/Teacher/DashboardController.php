<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('teacher.dashboard.index');
    }

    public function profile()
    {
        return view('teacher.profile.index');
    }

    public function profileSetup()
    {
        return view('teacher.profile.setup');
    }
}
