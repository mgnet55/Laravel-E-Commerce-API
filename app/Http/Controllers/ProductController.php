<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;


class ProductController extends ApiResponse
{

//   function __construct()
//   {
//        $this->middleware('permission:index product|add product|edit product|delete product', ['only' => ['index','show']]);
//        $this->middleware('permission:add product', ['only' => ['create','store']]);
//        $this->middleware('permission:edit product', ['only' => ['edit','update']]);
//        $this->middleware('permission:delete product', ['only' => ['destroy']]);
//     }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    function index()
    {
        $name = request()->query('search', 'none');
        //return $name;
        if ($name!='none') {
            $products = Product::where('name', 'LIKE', "%{$name}%")->where('available','>',0)->orderBy('id', 'desc')->with('category:id,name')->paginate(30);
        }
        else {
            $products = Product::latest()->where('available','=',true)->with('category:id,name')->paginate(30);

        }
        return $this->handleResponse($products, 'products');
    }

    function productsByCategory($category_id)
    {

        $products = Product::where('available','=',true)->where('category_id', '=', $category_id)->orderBy('id', 'desc')->with('category:id,name')->paginate(30);

        return $this->handleResponse($products, 'products');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    function store(ProductRequest $request)
    {
        $imageName = 'prod_' . time() . '.' . $request->image->extension();
        $request->image->move(public_path('products'), $imageName);
        $input = Product::firstOrCreate([...$request->except('image'), 'image' => $imageName]);
        if ($input) {

            return $this->handleResponse($input, 'Product added successfully!');
        } else {
            return $this->handleError('Failed.', ['product not added'], 402);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product)
    {
        if ($product) {

            return $this->handleResponse($product, 'Done!');
        } else {
            return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     * @return Response
     */


    function update(ProductRequest $request, Product $product)
    {

        if ($request->hasFile('image')) {
            $oldImageName = $product->image;
            $imageName = substr($oldImageName, 0, strrpos($oldImageName, ".")) . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
        }
        $input = $product->updateOrFail($request->all());
        if ($input) {

            return $this->handleResponse($input, 'Product has been updated successfully!');
        } else {
            return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     */

    function destroy(Product $product)
    {

        $imageName = $product->image;
        $delete = $product->deleteOrFail();

        if ($delete) {

            if (File::exists(public_path('products/' . $imageName))) {
                File::delete(public_path('products/' . $imageName));
            }
            return $this->handleResponse($delete, 'Product has been deleted successfully!');
        } else {
            return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }

    }


}
