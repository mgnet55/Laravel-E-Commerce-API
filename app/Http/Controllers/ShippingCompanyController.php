<?php

namespace App\Http\Controllers;

use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Auth;

class ShippingCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected ShippingCompany $shippingCompany;

    public function __construct()
    {
        $this->seller = Auth::user()->seller;
    }

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
