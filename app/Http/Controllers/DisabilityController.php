<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
USE Illuminate\Http\Request;
use App\Models\Disability;

class DisabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disability = Disability::all();

        return json_decode($disability);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
    }
}
