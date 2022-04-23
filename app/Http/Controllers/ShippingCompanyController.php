<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ShippingCompanyRequest;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Auth;

class ShippingCompanyController extends ApiResponse
{

    protected ShippingCompany $shippingCompany;

    public function __construct()
    {
        $this->middleware('role:admin|shipping_manager');
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
    public  function store(ShippingCompanyRequest $request)
    {
        return $request;
    }

    public function orders()
    {

    }

    public function waiting()
    {
    }
}
