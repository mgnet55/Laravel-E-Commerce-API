<?php
/** @noinspection PhpUndefinedFieldInspection */
/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SellerController extends ApiResponse
{

    public function __construct()
    {
        $this->middleware(['role:seller']);
    }

    public function allOrders(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->allOrders()->paginate(30),);
    }

    public function pendingOrders(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->pendingOrders()->paginate(30));
    }

    public function pickedOrders(): \Illuminate\Http\JsonResponse
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
        return $this->handleResponse(Auth::user()->seller->products()->with('category')->paginate(30));
    }

    public function availableProducts(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->availableProducts()->with('category')->paginate(30));
    }

    public function unavailableProducts(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->unavailableProducts()->with('category')->paginate(30));
    }

    public function zeroStock(): \Illuminate\Http\JsonResponse
    {
        return $this->handleResponse(Auth::user()->seller->zeroStock()->with('category')->paginate(30));

    }

    /**
     * @throws Throwable
     */
    public function createProduct(ProductRequest $request): \Illuminate\Http\JsonResponse
    {
        return (new ProductController)->store($request, Auth::id());
    }

    /**
     * @throws Throwable
     */
    public function updateProduct(ProductRequest $request, Product $product): \Illuminate\Http\JsonResponse
    {
        if (Auth::id() == $product->seller_id) {
            return (new ProductController)->update($request, $product);
        }
        return $this->handleError('Not your product', ['Not your product'], 403);
    }

    /**
     * @throws Throwable
     */
    public function deleteProduct(Product $product): \Illuminate\Http\JsonResponse
    {
        if (Auth::id() == $product->seller_id) {
            return (new ProductController)->destroy($product);
        }
        return $this->handleError('Not your product', ['Not your product'], 403);
    }

    public function showProduct(Product $product): \Illuminate\Http\JsonResponse
    {
        if (Auth::id() == $product->seller_id) {
            return (new ProductController)->show($product);
        }
        return $this->handleError('Unauthorized', ['Not your product'], 403);
    }


}


