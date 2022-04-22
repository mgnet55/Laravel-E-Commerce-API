<?php

namespace App\Http\Controllers;
use App\Models\Order;


class CustomerController extends Controller
{
     public function getOrders(){

        $customer = auth()->user()->customer;

        return $customer->orders;
     }

     public function orderDetails($id){

     $order =  Order::where('id','=',$id)->where('customer_id','=',auth()->id())->first();

        return $order->orderItems;

     }

}
