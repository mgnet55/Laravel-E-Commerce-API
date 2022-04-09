<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;


class CheckoutController extends Controller
{
    public function charge(Request $request)
    {
        $cart=auth()->user()->cart;
        $items=$cart->items()->with('product:id,price')->get();
        $totalPrice=0;
        foreach ($items as $item)
        {
            $totalPrice+=$item->quantity*$item->product->price;
        }
        $token = Stripe::tokens()->create([
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 6,
                'exp_year'  => 2024,
                'cvc'       => 314,
            ],
        ]);
        try {
            $charge= Stripe::charges()->create([
                'currency' =>'USD',
                'source'  =>$token['id'],
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
                $order->user_id=$cart->user_id;
                $order->notes=$cart->notes;
                if($order->save())
                {
                    
                }
            }catch (\Exception $e)
            {

            }
            return 'sucess';
        }
        return 'faild';
    }
}
