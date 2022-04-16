<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
     public function getOrders(){

        $customer = auth()->user()->customer;

        return $customer->orders;
     }

     public function orderDetails($id){

     $order =  Order::where('id','=',$id)->where('user_id','=',auth()->id())->first();

        return $order->orderItems;

     }

}
