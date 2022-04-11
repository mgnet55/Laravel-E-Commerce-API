<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
//        return Order::class->
    }

    public function getOrderDetails($id){

        $order = Order::findOrFail($id);
        return $order->orderItems;
    }
}