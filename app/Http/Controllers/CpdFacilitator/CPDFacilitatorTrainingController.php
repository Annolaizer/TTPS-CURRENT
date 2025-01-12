<?php

namespace App\Http\Controllers\CpdFacilitator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CPDFacilitatorTrainingController extends Controller
{
    public function index()
    {
        return view('cpd_facilitator.training.index');
    }
}
