<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;


class CheckoutController extends Controller
{
    public function charge(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $token=$request->all();
        $cart=auth()->user()->cart;
        $items=$cart->items()->with('product')->get();
        $totalPrice=0;

        foreach ($items as $item)
        {
            $totalPrice+=$item->quantity*$item->product->price;
        }
//        $token = Stripe::tokens()->create([
//            'card' => ['number'    => '4242424242424242',
//                'exp_month' => 6,
//                'exp_year'  => 2024,
//                'cvc'       => 314,
//            ],
//        ]);
        try {
            $charge= Stripe::charges()->create([
                'currency' =>'USD',
                'source'  =>$token['stripeToken'],
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
                if($order->save()) {
                    foreach ($items as $item) {
                        $orderItem = new OrderItem();
                        $orderItem->name = $item->product->name;
                        $orderItem->description = $item->product->description;
                        $orderItem->quantity = $item->quantity;
                        $orderItem->price = $item->product->price;
                        $orderItem->image = $item->product->image;
                        $orderItem->user_id = $item->product->user_id;
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

            return response(['success'=>'فلوسك ضاعت وضحكنا عليك وابقا قابلنى لو جالك اوردر'],201);
        }
        return response(['faild'=>'لاسف معرفناش نسرقك ابقا دخل فيزا فيها فلوس'],404);
    }
}
