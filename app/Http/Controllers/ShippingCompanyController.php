<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Auth;

class ShippingCompanyController extends ApiResponse
{

    protected ShippingCompany $shippingCompany;

    public function __construct()
    {
        $this->shippingCompany = Auth::user()->ShippingCompany;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = ShippingCompany::all();
        return $companies;
    }


    public function orders()
    {

    }

    public function waiting()
    {
    }
}
