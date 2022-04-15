<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SellerController extends ApiResponse
{
//    public function __call($method, array $args)
//    {
//        if (auth()->user()->hasRole('seller')) {
//                return call_user_func_array([$this, $method], $args);
//        }
//    }
//
    //protected Seller $seller=new Seller();
    protected ProductController $productController;

    public function __construct()
    {
        //return Auth()->user();
        //Auth::user()->seller =new Seller;
        // User::where('id','=',Auth::id())->get()->seller;
        //$this->productController = new ProductController;
    }

    public function authorize($ability, $arguments = []): \Illuminate\Auth\Access\Response
    {
        return auth()->user()->hasRole('seller');
    }

    public function allOrders(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->orderItems()->paginate(30),);
    }

    public function pendingOrders(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->pendingOrders()->paginate(30));
    }

    public function sentOrders(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->pickedOrders()->paginate(30));
    }

    public function fulfilled(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->fulfilled()->paginate(30));
    }

    public function unfulfilled(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->unfulfilled()->paginate(30));
    }

    public function products(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->products()->paginate(30));
    }

    /**
     * @throws \Throwable
     */
    public function createProduct(ProductRequest $request)
    {
        $product = new Product($request->all());
        $product->user_id = Auth::id();
        $product->saveOrFail();
    }

    public function updateProduct(ProductRequest $request, Product $product)
    {
        if ($seller->id == $product->user_id) {
            $product->updateOrFail($request->all());
        } else abort(403);
    }

    public function deleteProduct(Product $product)
    {
        if ($seller->id == $product->user_id) {
            $product->deleteOrFail();
        } else abort(403);

    }
}


