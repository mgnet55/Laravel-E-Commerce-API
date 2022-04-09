<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;

use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index()
    {
        $cart=  auth()->user()->cart;

        if($cart)
        {
            return $cart->items()->with('product')->get();
        }
        $cart=new Cart();
        $cart->user_id=auth()->user()->id;
        $cart->save();
        return $cart;
    }

    public function addItem(Product $product)
    {
        $cart= auth()->user()->cart;
        if(!empty($cart->items))
        {
            foreach ($cart->items as $item)
            {
                if ($item->product_id==$product->id)
                {
                    $item->quantity+=1;
                    $item->save();
                    return 'add success';
                }
            }
            $item=new CartProduct();
            $item->cart_id=$cart->id;
            $item->product_id=$product->id;
            $item->quantity=1;
            $item->save();
            return 'add another one  ';
        }
        $cart=new Cart();
        $cart->user_id=auth()->user()->id;
        $cart->save();
        $item=new CartProduct();
        $item->cart_id=$cart->id;
        $item->product_id=$product->id;
        $item->quantity=1;
        $item->save();
        return 'add another one  ';
    }
    public function removeItem(Product $product)
    {
        $cart=auth()->user()->cart;
        foreach ($cart->items as $item)
        {
            if ($item->product_id==$product->id)
            {
                $item->delete();
                return 'removed success';
            }
        }
        return abort(404);

    }
    public function info(Request $request)
    {
        $request->validate([
            'city_id'=>'required|exists:cities,id',
            'street' =>'required|string',
            'notes'=>'string'
        ]);
        try {
            $cart=auth()->user()->cart;
            $cart->city_id=$request['city_id'];
            $cart->street=$request['street'];
            $cart->shipping_company_id=1;
            $cart->notes=$request['notes'];
            $cart->save();
            return 'success';
        }catch (\Exception $e)
        {
            return 'filed';
        }
    }
}
