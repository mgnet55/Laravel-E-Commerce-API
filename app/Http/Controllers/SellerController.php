<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Seller;
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
    protected Seller $seller;
    protected ProductController $productController;

    public function __construct()
    {
        $this->seller = Auth::user()->seller;
        $this->productController = new ProductController;
    }

    public function authorize($ability, $arguments = [])
    {
        return auth()->user()->hasRole('seller');
    }

    public function allOrders()
    {
        return $this->handleResponse($this->seller->orderItems()->paginate(30),);
    }

    public function perndingOrders()
    {
        return $this->handleResponse($this->seller->pendingOrders()->paginate(30));
    }

    public function sentOrders()
    {
        return $this->handleResponse($this->seller->sentOrders()->paginate(30));
    }

    public function fulfilled()
    {
        return $this->handleResponse($this->seller->fulfilled()->paginate(30));
    }

    public function unfulfilled()
    {
        return $this->handleResponse($this->seller->unfulfilled()->paginate(30));
    }

    public function products()
    {
        return $this->handleResponse($this->seller->products()->paginate(30));
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
            $this->productController->updateOrFail($request, $product);
        } else abort(403);
    }

    public function deleteProduct(Product $product)
    {
        if ($seller->id == $product->user_id) {
            $product->deleteOrFail();
        } else abort(403);

    }
}


