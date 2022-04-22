<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends ApiResponse
{
    public function index()
    {
//        return Order::class->
    }

    public function show(Order $order){

        $this->handleResponse($order->with('orderItems'),'order details') ;
    }
}
