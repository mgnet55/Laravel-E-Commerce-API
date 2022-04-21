<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;


class CheckoutController extends Controller
{
    public function charge(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $token=$request->all();
        $user= auth()->user();
        $cart=$user->customer->cart;
        $items=$cart->items()->with('product')->get();
        $totalPrice=0;
        foreach ($items as $item)
        {
            $totalPrice+=$item->quantity*$item->product->price;
        }
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
                $order->customer_id=$cart->user_id;
                $order->notes=$cart->notes;
                if($order->save()) {
                    foreach ($items as $item) {
                        $orderItem = new OrderItems();
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
