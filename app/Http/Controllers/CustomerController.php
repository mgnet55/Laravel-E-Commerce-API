<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Classes\ImageManager;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\ApiResponse;

class CustomerController extends ApiResponse
{
    public function orders()
    {

        $orders = auth()->user()->customer->orders()->paginate(30);
        return $this->handleResponse($orders, 'your orders');

    }

    public function orderDetails(Order $order)
    {
        if ($order->customer_id == Auth::id()) {
            return $this->handleResponse($order, 'Order details');
        }

        return $this->handleError('Not Your Order', ['Not Your Order']);

    }
}
