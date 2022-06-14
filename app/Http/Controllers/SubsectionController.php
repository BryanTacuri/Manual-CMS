<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubsectionRequest;
use App\Http\Requests\UpdateSubsectionRequest;
use App\Models\Subsection;

class SubsectionController extends Controller
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
     * @param  \App\Http\Requests\StoreSubsectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubsectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subsection  $subsection
     * @return \Illuminate\Http\Response
     */
    public function show(Subsection $subsection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subsection  $subsection
     * @return \Illuminate\Http\Response
     */
    public function edit(Subsection $subsection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSubsectionRequest  $request
     * @param  \App\Models\Subsection  $subsection
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubsectionRequest $request, Subsection $subsection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subsection  $subsection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subsection $subsection)
    {
        //
    }
}
