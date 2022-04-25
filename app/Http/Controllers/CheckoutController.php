<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;


class CheckoutController extends ApiResponse
{
    public function charge(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $tokens=$request->all();
        $user= auth()->user();
        $cart=$user->customer->cart;
        $items=$cart->items()->with('product')->get();
        $totalPrice=0;
        foreach ($items as $item)
        {
            if ($item->product->discount)
                $totalPrice+=$item->quantity*$item->product->price*$item->product->discount;
            else
                $totalPrice+=$item->quantity*$item->product->price;
        }
        try {
            $charge= Stripe::charges()->create([
                'currency' =>'USD',
                'source'  =>$tokens['stripeToken'],
                'amount'  =>$totalPrice,
            ]);
        }catch (\Exception $e)
        {
            return $e->getMessage();
        }
        if($charge['id'])
        {
            try {
                $order=new Order();
                $order->payment_ref=$charge['id'];
                $order->city_id=$cart->city_id;
                $order->street=$cart->street;
                $order->shipping_company_id=$cart->shipping_company_id;
                $order->customer_id=$cart->customer_id;
                $order->notes=$cart->notes;
                if($order->save()) {
                    foreach ($items as $item) {
                        $orderItem = new OrderItems();
                        $orderItem->name = $item->product->name;
                        $orderItem->description = $item->product->description;
                        $orderItem->quantity = $item->quantity;
                        $orderItem->price = $item->product->price;
                        $orderItem->image = $item->product->image;
                        $orderItem->product_id = $item->product->id;
                        $orderItem->discount=$item->product->discount;
                        $orderItem->order_id=$order->id;
                        $orderItem->save();
                        $product=Product::where('id','=',$item->product->id)->get()->first();
                        $product->quantity-=$item->quantity;
                        $product->save();
                    }
                    $cart->delete();
                }
            }catch (\Exception $e)
            {
                return $e->getMessage();
            }

            return $this->handleResponse('success','Order Placed successfully',201);
        }
        return $this->handleError('filed','something wrong please try again',402);
    }
}

