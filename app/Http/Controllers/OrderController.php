<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\ApiResponse;

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

      // --------------------- Payments---------------------------------------

    public function unfulfilled($sellerID){

        $orders = DB::table('order_items')
        ->whereIn('product_id', function ($query) use($sellerID) {
            $query->select('id')->from('products')->where('seller_id', $sellerID);
        })
        ->where('fulfilled', '=', false)
        ->where('picked', '=', true)
        ->select('id','product_id','name' ,'created_at', 'price', 'picked', 'fulfilled', 'quantity')
        ->latest()->paginate(10);

        return $this->handleResponse($orders);
    }

    public function fulfilled($sellerID){

        $orders = DB::table('order_items')
        ->whereIn('product_id', function ($query) use($sellerID) {
            $query->select('id')->from('products')->where('seller_id', $sellerID);
        })
        ->where('fulfilled', '=', true)
        ->where('picked', '=', true)
        ->select('id','product_id','name' ,'created_at', 'price', 'picked', 'fulfilled', 'quantity')
         ->latest()->paginate(10);

        return $this->handleResponse($orders);
    }

    // --------------------- Orders---------------------------------------

    // Processing orders & not  picked------------------
    public function processingOrders(){

        $processing = DB::table('orders')
            ->where('status', 'Processing')
            ->whereIn('id', function ($query) {
                $query->select('order_id')->from('order_items')->where('picked', 0);
            })->select('id', 'created_at', 'shipping_company_id', 'status')
            ->latest()->paginate(10);

        return $this->handleResponse($processing);
    }

    // Processing orders & Picked------------------
    public function pickedOrders(){

        $orders = DB::table('orders')
            ->whereIn('id', function ($query) {
                $query->select('order_id')->from('order_items')->where('picked', 1);
            })
            ->whereNotIn('id', function ($query) {
                $query->select('order_id')->from('order_items')->where('picked', 0);
            })
            ->where('status', 'Processing')
            ->select('id', 'created_at', 'shipping_company_id', 'status')
            ->latest()->paginate(10);

        return $this->handleResponse($orders);
    }

    // On-war orders &  picked------------------
    public function onWayOrders(){

        $onWay = DB::table('orders')
            ->where('status', 'On way')
            ->select('id', 'created_at', 'shipping_company_id', 'status')
            ->latest()->paginate(10);

        return $this->handleResponse($onWay);
    }

    public function orderDetails($id){
        $orders = DB::table('order_items')
            ->where('order_id', $id)
            ->select('product_id', 'created_at', 'price', 'picked', 'fulfilled', 'quantity')
            ->latest()->paginate(10);

        return $this->handleResponse($orders);
    }

      // --------------------- Orders Functions---------------------------------------

    public function setOnWay(Order $order){
        
        if ($order->status == 'Processing') {
            $order->status = 'on way';
            $order->save();

            return $this->handleResponse('Success', 'On-way Status Updated Successfully!');
        }
        return $this->handleError('Failed', ["Failed to update status"], 402);
    }

    public function setDone(Order $order){
        if ($order->status == 'On way') {
            $order->status = 'Done';
            $order->save();

            return $this->handleResponse('Success', 'Done Status Updated Successfully!');
        }
        return $this->handleError('Failed', ["Failed to update status"], 402);
    }

    public function setFulfilled($id){

        $item = OrderItems::where('id', $id)->first();

        if($item->fulfilled == 0){
            $item->fulfilled = 1;
            $item->save();
            return $this->handleResponse('Success', 'Fulfilled Status Updated Successfully!');
        }

        return $this->handleError('Failed', ["Failed to update status"], 402);
    }

}
