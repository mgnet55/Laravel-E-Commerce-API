<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProfileUpdateRequest;

class UserController extends ApiResponse
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegisterRequest $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProfile(){


        $user = User::find(Auth::id());
        $user->city;

        return $this->handleResponse($user, 'Done!');

    }

    public function updateProfile(ProfileUpdateRequest $request)
    {

        $user = Auth::user();

        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->city_id = $request['city_id'];

        if($user->save()){
          return handleResponse($user,'Successfully Updated');
        }else{
          return handleError('failed','failed to update profile');
        }
    }
}
