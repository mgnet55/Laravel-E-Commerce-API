<?php

namespace App\Http\Controllers;

use App\Classes\ImageManager;
use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;


class ProductController extends ApiResponse
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    function index()
    {
        $name = request()->query('search', 'none');
        //return $name;
        if ($name != 'none') {
            $products = Product::where('name', 'LIKE', "%{$name}%")->where('available', '>', 0)->orderBy('id', 'desc')->with('category:id,name')->paginate(24);
        } else {
            $products = Product::latest()->where('available', '=', true)->with('category:id,name')->paginate(24);

        }
        return $this->handleResponse($products, 'products');
    }

    function allProducts(): JsonResponse
    {
        $name = request()->query('search', 'none');
        $products;
        if ($name != 'none') {
            return $this->handleResponse(Product::where('name', 'LIKE', "%{$name}%")->orderBy('id', 'desc')->with('category:id,name')->paginate(24), 'products search');
        } else
            return $this->handleResponse(Product::orderBy('id', 'desc')->with('category:id,name')->paginate(24), 'products');
    }

    function productsByCategory($category_id)
    {

        $products = Product::where('available', '=', true)->where('category_id', '=', $category_id)->orderBy('id', 'desc')->with('category:id,name')->paginate(24);

        return $this->handleResponse($products, 'products');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @param int $userId
     * @return JsonResponse
     * @throws Throwable
     */
    function store(ProductRequest $request, int $sellerId): JsonResponse
    {
        if (Auth::user()->hasPermissionTo('create product')) {
            $found = Product::where('name', '=', $request->get('name'))->where('seller_id', '=', $sellerId)->first();
            if ($found) {
                return $this->handleError('Product already exists', ['Product already exists'], 409);
            }
            $product = new Product($request->all());
            $product->seller_id = $sellerId;
            $product->image = ImageManager::generateName($request, 'image', 'product');
            if ($product->saveOrFail()) {
                ImageManager::upload($request, 'image', 'products', $product->image);
                return $this->handleResponse($product, 'Product created Successfully');
            }
            return $this->handleError('Failed to save product', ['Failed to save product']);
        }
        return $this->handleError('Unauthorized', ["You don't have the permission to create product"], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product)
    {
        // return $this->handleResponse($product, 'product');
        //    return  auth()->user()->seller->products()->where('id' == $product->id)->get();
        //  return $product->seller_id;

        return $this->handleResponse($product, 'Product Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     * @throws Throwable
     */

    function update(ProductRequest $request, Product $product)
    {
        if (Auth::user()->hasPermissionTo('update product')) {
            if ($product->updateOrFail($request->except('image'))) {
                ImageManager::update($request, 'image', $product->image, 'products');
                return $this->handleResponse($product, 'Product Updated Successfully');
            }
            return $this->handleError('Failed to save product', ['Failed to save product'], 402);
        }
        return $this->handleError('Unauthorized', ["You don't have the permission to update product"], 403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return JsonResponse
     * @throws Throwable
     */

    function destroy(Product $product): JsonResponse
    {
        if ($product->deleteOrFail()) {
            ImageManager::delete($product->image, 'products');;
            return $this->handleResponse($product, 'Product deleted Successfully');
        }
        return $this->handleError('Failed to save product', ['Failed to save product'], 402);
    }


    function orders(Product $product): JsonResponse
    {
        $productOrders = $product->ordered()->get();
        $product->seller = $product->seller()->get(['id', 'name']);
        return $this->handleResponse(['product' => $product, 'orderItems' => $productOrders], 'product with orders');
    }

    function deleted(){
        return $this->handleResponse(Product::onlyTrashed()->orderBy('id', 'desc')->with('category:id,name')->paginate(24), 'trashed products');
    }




    public function statistic()
    {
        $flashSale=Product::where('available', '=', true)->where('discount','>',0.69)->get();
        $sale50=Product::where('available', '=', true)->where('discount','<',0.51)->get();
        $best70=Product::where('available', '=', true)->whereBetween('discount',[0.51,0.71])->get();
        $lastProduct=Product::latest()->where('available', '=', true)->paginate(24);
        return $this->handleResponse([
            'sale50'=>$sale50,
            'best70'=>$best70,
            'lastProduct'=>$lastProduct,
            'flashSale'=>$flashSale
        ],'data');
    }
}
