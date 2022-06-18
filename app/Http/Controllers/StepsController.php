<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStepsRequest;
use App\Http\Requests\UpdateStepsRequest;
use App\Models\Steps;

class StepsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStepsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStepsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Steps  $steps
     * @return \Illuminate\Http\Response
     */
    public function show(Steps $steps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Steps  $steps
     * @return \Illuminate\Http\Response
     */
    public function edit(Steps $steps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStepsRequest  $request
     * @param  \App\Models\Steps  $steps
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStepsRequest $request, Steps $steps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Steps  $steps
     * @return \Illuminate\Http\Response
     */
    public function destroy(Steps $steps)
    {
        //
    }
}
