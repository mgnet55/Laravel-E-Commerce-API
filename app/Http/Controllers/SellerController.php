<?php /** @noinspection PhpUndefinedFieldInspection */

/** @noinspection PhpUnused */

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
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
        if ($product->saveOrFail()) {
            return $this->handleResponse('', 'Create Successfully');
        }
        return $this->handleError('failed', 'Failed to create product');
    }

    public function updateProduct(ProductRequest $request, Product $product)
    {
        if ($seller->id == $product->user_id) {
            if ($product->updateOrFail($request->all())) {
                return $this->handleResponse('', 'Updated Successfully');
            } else {
                return $this->handleError('failed', 'Failed to update product');
            }
        }
        return $this->handleError('unauthorized', 'Not your product', 403);
    }

    public function deleteProduct(Product $product)
    {
        if ($seller->id == $product->user_id) {
            if ($product->deleteOrFail()) {
                return $this->handleResponse('', 'Deleted Successfully');
            } else {
                return $this->handleError('failed', 'Failed to delete product');
            }
        }
        return $this->handleError('unauthorized', 'Not your product', 403);

    }

}


