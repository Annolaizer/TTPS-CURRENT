<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherProfileController extends Controller
{
    function index(){
        return  view('teacher.training.index');
    }
}
