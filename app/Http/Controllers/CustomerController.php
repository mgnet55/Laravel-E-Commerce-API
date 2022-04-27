<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use App\Classes\ImageManager;
use Illuminate\Support\Facades\Auth;

class CustomerController extends ApiResponse
{

   public function destroy($id)

    {

        $customer = User::where('id', $id)->first();

        // return $customer;

        if ($customer->deleteOrFail()) {
            // ImageManager::delete($customer->avatar, 'customers');
            return $this->handleResponse($customer, 'customer deleted Successfully');
        }
        return $this->handleError('Failed to delete customer', ['Failed to delete customer'], 402);
    }
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
