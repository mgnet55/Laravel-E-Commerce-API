<?php

namespace App\Http\Controllers;

use App\Http\Requests\GovernorateRequest;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Governorate::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GovernorateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GovernorateRequest $request)
    {
        Governorate::firtOrCreate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Governorate $governorate
     * @return \Illuminate\Http\Response
     */
    public function show(Governorate $governorate)
    {
        return $governorate->cities()->get(['id','name']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GovernorateRequest $request,Governorate $governorate)
    {
        return $governorate->updateOrFail($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Governorate $governorate)
    {
        return $governorate->deleteOrFail();
    }
}
