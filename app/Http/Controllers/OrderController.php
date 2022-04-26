<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiResponse
{
    public function index()
    {
//        return Order::class->
    }

    public function show(Order $order)
    {
        $this->handleResponse($order->with('orderItems'), 'order details');
    }

    // Admin Orders Management -------------------------------------------

    public function unfulfilled()
    {
        $orders =  DB::table('orders')
        ->whereIn('id', function ($query) {
            $query->select('order_id')->from('order_items')->where('fulfilled', 0);
        })
        ->select('id', 'created_at','shipping_company_id','status')
        ->latest()->paginate(10);

         return $this->handleResponse($orders);
    }

    public function fulfilled()
    {
        $orders =  DB::table('orders')
            ->whereIn('id', function ($query) {
                $query->select('order_id')->from('order_items')->where('fulfilled', 1);
            })
            ->whereNotIn('id', function ($query) {
                $query->select('order_id')->from('order_items')->where('fulfilled', 0);
            })
            ->where('status','Processing')
            ->select('id', 'created_at','shipping_company_id','status')
            ->latest()->paginate(10);

       return $this->handleResponse($orders);
    }

    public function onWayOrders()
    {
        $onWay = DB::table('orders')
            ->where('status','On way')
            ->select('id', 'created_at','shipping_company_id','status')
            ->latest()->paginate(10);

        return $this->handleResponse($onWay);
    }

    public function processingOrders()
    {

         $processing = DB::table('orders')
            ->where('status','Processing')
            ->select('id', 'created_at','shipping_company_id','status')
            ->latest()->paginate(10);

        return $this->handleResponse($processing);
    }

    public function setOnWay(Order $order)
    {
        if ($order->status == 'Processing') {
            $order->status = 'on way';
            $order->save();

            return $this->handleResponse('Success', 'On-way Status Updated Successfully!');
        }
        return $this->handleError('Failed', ["Failed to update status"], 402);
    }

    public function setDone(Order $order)
    {
        if ($order->status == 'On way') {
            $order->status = 'Done';
            $order->save();

            return $this->handleResponse('Success', 'Done Status Updated Successfully!');
        }
        return $this->handleError('Failed', ["Failed to update status"], 402);
    }

    public function orderDetails($id)
    {
        $orders = DB::table('order_items')
        ->where('order_id', $id)
        ->select('product_id', 'created_at','price','picked','fulfilled','quantity')
        ->latest()->paginate(10);

        return $this->handleResponse($orders);
    }

}
