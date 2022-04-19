<?php

namespace App\Http\Controllers;

use App\Http\Requests\GovernorateRequest;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Governorate::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GovernorateRequest $request
     * @return Response
     */
    public function store(GovernorateRequest $request)
    {
        Governorate::firtOrCreate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param Governorate $governorate
     * @return Response
     */
    public function show(Governorate $governorate)
    {
        return $governorate->cities()->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(GovernorateRequest $request,Governorate $governorate)
    {
        return $governorate->updateOrFail($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Governorate $governorate)
    {
        return $governorate->deleteOrFail();
    }
}
