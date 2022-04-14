<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    public function index()
    {
        $user= auth()->user();
        $cart=$user->customer->cart->first();
        if($cart)
        {
            $items=$cart->items()->with('product')->get();
            $totalPrice=0;
            foreach ($items as $item)
            {
                $totalPrice+=$item->quantity*$item->product->price;
            }
            return response(['cart'=>$cart->items()->with('product')->get(),
                'totalPrice'=>$totalPrice]);
        }
        $cart=new Cart();
        $cart->user_id=auth()->user()->id;
        $cart->save();
        return $cart;
    }
    public function addItem(Product $product,Request $request)
    {
        $request->validate(
            ['quantity'=>'required|numeric']
        );
        $quantity=$request->quantity;
        if($quantity>$product->quantity)
        {
            return response(['messeage'=>'this quantity not availble ']);
        }
        $user= auth()->user();
        $cart=$user->customer->cart;
        if(!empty($cart->items))
        {
            foreach ($cart->items as $item)
            {
                if ($item->product_id==$product->id)
                {
                    $item->quantity=$quantity;
                    $item->save();
                    return 'add success';
                }
            }
            $item=new CartProduct();
            $item->cart_id=$cart->id;
            $item->product_id=$product->id;
            $item->quantity=$quantity;
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
        $user= auth()->user();
        $cart=$user->customer->cart;
        foreach ($cart->items as $item)
        {
            if ($item->product_id==$product->id)
            {
                $item->delete();
//                return 'removed success';
            }
        }
    }
    public function setCartInfo(Request $request)
    {
        $request->validate([
            'city_id'=>'required|exists:cities,id',
            'street' =>'required|string',
            'notes'=>'string'
        ]);

        try {
            $user= auth()->user();
            $cart=$user->customer->cart;
            $cart->city_id=$request['city_id'];
            $cart->street=$request['street'];
            $cart->shipping_company_id=1;
            $cart->notes=$request['notes'];
            $cart->save();
            return 'success';
        }catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }
    public function getCartInfo()
    {
         $user= auth()->user();
        return $user->customer->cart;;
    }
    public function update(Request $request)
    {
        $products=$request->all();
        $user= auth()->user();
        $cart=$user->customer->cart;
        $items=$cart->items()->get();
        foreach ($products as $product)
        {
            foreach ($items as $item)
            {
                if($product['id']==$item->id)
                {
                    $item->quantity=$product['quantity'];
                    $item->save();
                }
            }
        }
    }
    public function getItemsNumber()
    {
        $user= auth()->user();
        $cart=$user->customer->cart;
        $totalQuantity=DB::table('cart_products')
            ->where('cart_id','=',$cart->id)
            ->sum('quantity');
        return $totalQuantity;
    }
}
