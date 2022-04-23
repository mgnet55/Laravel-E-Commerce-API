<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Http\Controllers\API\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CartController extends ApiResponse
{
    public function index()
    {
        $user= auth()->user();
        $cart=$user->customer->cart;
        if($cart)
        {
            $items=$cart->items()->with('product')->get();
            $totalPrice=0;
            foreach ($items as $item)
            {
                if ($item->product->discount)
                    $totalPrice+=$item->quantity*$item->product->price*$item->product->discount;
                else
                    $totalPrice+=$item->quantity*$item->product->price;
            }
            $totalQuantity=DB::table('cart_products')
                ->where('cart_id','=',$cart->id)
                ->sum('quantity');
            return $this->handleResponse(['cart'=>$cart,'items'=>$cart->items()->with('product')->get(),
                'totalPrice'=>$totalPrice,
                'totalQuantity'=>$totalQuantity], 'cart');
        }
        $cart=new Cart();
        $cart->user_id=auth()->user()->id;
        $cart->save();
        return $this->handleResponse(['cart'=>$cart],'cart');
    }
    public function addItem(Product $product,Request $request)
    {
        $request->validate(
            ['quantity'=>'required|numeric']
        );
        $quantity=$request->quantity;
        if($quantity>$product->quantity||$quantity<1||$quantity=='')
        {
            return $this->handleError('Failed.', 'this quantity not available',422 );
        }
        $user= auth()->user();
        $cart=$user->customer->cart;
        if(!empty($cart->items))
        {
            $items=$cart->items()->with('product')->get();
            foreach ($items as $item)
            {
                if ($item->product_id==$product->id)
                {
                    if($item->quantity==$item->product->quantity)
                    {
                        return $this->handleError('Failed.', 'this quantity not available',422);
                    }
                    $item->quantity+=$quantity;
                    $item->save();
                    $totalQuantity=DB::table('cart_products')
                        ->where('cart_id','=',$cart->id)
                        ->sum('quantity');
                    return $this->handleResponse(['totalQuantity'=>$totalQuantity],'add successfully');
                }
            }
            $item=new CartProduct();
            $item->cart_id=$cart->id;
            $item->product_id=$product->id;
            $item->quantity=$quantity;
            $item->save();
            $totalQuantity=DB::table('cart_products')
                ->where('cart_id','=',$cart->id)
                ->sum('quantity');
            return $this->handleResponse(['totalQuantity'=>$totalQuantity],'add successfully');
        }
        $cart=new Cart();
        $cart->customer_id=auth()->user()->id;
        $cart->save();
        $item=new CartProduct();
        $item->cart_id=$cart->id;
        $item->product_id=$product->id;
        $item->quantity=1;
        $item->save();
        $totalQuantity=DB::table('cart_products')
            ->where('cart_id','=',$cart->id)
            ->sum('quantity');
        return $this->handleResponse(['totalQuantity'=>$totalQuantity],'add successfully');
    }
    public function removeItem(Product $product)
    {
        $user= auth()->user();
        $cart=$user->customer->cart;
        foreach ($cart->items as $item)
        {
            if ($item->product_id==$product->id) {
                $item->delete();
                $totalPrice = 0;
                foreach ($cart->items as $item) {
                    if ($item->product->discount)
                        $totalPrice+=$item->quantity*$item->product->price*$item->product->discount;
                    else
                        $totalPrice+=$item->quantity*$item->product->price;
                }
                $totalQuantity = DB::table('cart_products')
                    ->where('cart_id', '=', $cart->id)
                    ->sum('quantity');
                return $this->handleResponse(['cart'=>$cart,'items'=>$cart->items()->with('product')->get(),
                    'totalPrice'=>$totalPrice,
                    'totalQuantity'=>$totalQuantity], 'item removed successfully');
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
            return $this->handleResponse($cart,'data is ok');
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
                    if(($product['quantity']>$item->product->quantity||$product['quantity']<1)||$product['quantity']=='')
                    {
                        return $this->handleError('Failed.', ['this quantity not available'], 402);
                    }
                    $item->quantity=$product['quantity'];
                    $item->save();
                }
            }
        }
        $totalPrice=0;
        foreach ($items as $item)
        {
            if ($item->product->discount)
            $totalPrice+=$item->quantity*$item->product->price*$item->product->discount;
            else
                $totalPrice+=$item->quantity*$item->product->price;
        }
        $totalQuantity=DB::table('cart_products')
            ->where('cart_id','=',$cart->id)
            ->sum('quantity');
        return $this->handleResponse(['cart'=>$cart,'items'=>$cart->items()->with('product')->get(),
            'totalPrice'=>$totalPrice,
            'totalQuantity'=>$totalQuantity], 'quantity updated successfully ');
    }
    public function getItemsNumber()
    {
        $user= auth()->user();
        $cart=$user->customer->cart;
        if($cart)
        {
            $totalQuantity=DB::table('cart_products')
                ->where('cart_id','=',$cart->id)
                ->sum('quantity');
            return $this->handleResponse($totalQuantity,'items quantity');
        }
        return $this->handleResponse(0,'you don\'t add any items to your cart');

    }
}
