<?php

namespace App\Http\Controllers;


use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\ImageManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;



class UserController extends ApiResponse
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RegisterRequest $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {

    }


    public function getProfile()
    {
        $user = User::find(Auth::id());
        
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

        if ($user->save()) {
            return $this->handleResponse($user, 'Successfully Updated');
        } else {
            return $this->handleError('Failed', 'Failed to update profile');
        }
    }


    public function customers(){
        $customers=User::whereHas("roles", function($q){ $q->where("name", "customer"); })->paginate();
        return $this->handleResponse($customers, 'customers');
    }




    // Admin Sellers Control --------------------------

    public function listingSellers(){
        $sellers = User::role('seller')->latest()->paginate(10);
        return $this->handleResponse($sellers);
    }

    public function updateActiveState($id){
        $seller = User::where('id', $id)->first();

        if($seller)
        {
            if ($seller->hasAnyRole('seller')) {

                if( $seller->active == 1){
                    $seller->active = false;
                    $seller->save();
                    return $this->handleResponse('Done', 'Seller State has been updated to Not Active.');
                }
                elseif ($seller->active == 0) {
                    $seller->active = true;
                    $seller->save();
                    return $this->handleResponse('Done', 'Seller State has been updated to Active.');
                }
            }
         }
       return $this->handleError('Failed', 'Failed to update the state');

    }

    // Show seller details from product ID --------------------

    public function sellerDetails($pID)
    {
        $sID = Product::where('id', $pID)->pluck('seller_id')->first();
        $user = User::where('id', $sID)->first();

             if($user){
                if($user->hasAnyRole('seller')){
                    return $this->handleResponse($user);
                }
             }
        return $this->handleError('Failed', 'Failed to load Data');

        // return $user->getRoleNames();
    }

}
