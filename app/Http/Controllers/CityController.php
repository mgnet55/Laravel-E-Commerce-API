<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends ApiResponse
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleResponse(City::all(),'cites');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {

        $found = City::where('name', '=', $request->get('name'))->where('governorate_id', '=', $request->get('governorate_id'))->first();
        if ($found) {
            return $this->handleError('City already exists', ['Product already exists'], 409);
        }


        return $this->handleResponse(
            City::Create([
                'name'=>$request->get('name'),
                'governorate_id'=>$request->get('governorate_id')
            ])
            ,'city created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return $this->handleResponse($city,'city');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request,City $city)
    {
        $city->update([
            'name'=>$request->get('name'),
            'governorate_id'=>$request->get('governorate_id')
        ]);
        return $this->handleResponse($city,'city updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();
        return $this->handleResponse('city','city deleted successfully');

    }
}
