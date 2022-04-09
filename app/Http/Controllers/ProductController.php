<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\API\ApiResponse;


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
     * @return \Illuminate\Http\Response
     */

    function index(){

      $products = Product::latest()->paginate(5);

        return $this->handleResponse($products);
        
      }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function store(ProductRequest $request)
    {
        $imageName = 'prod_' . time() . '.' . $request->image->extension();
        $request->image->move(public_path('products'), $imageName);
      $input = Product::firstOrCreate([...$request->except('image'), 'image' => $imageName]);
      if($input){

        return $this->handleResponse($input, 'Product added successfully!');
    } else {
        return $this->handleError('Failed.', ['product not added'],402);
    }
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if($product){

            return $this->handleResponse($product, 'Done!');
        } else {
            return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */


    function update(ProductRequest $request, Product $product){

        if ($request->hasFile('image')) {
            $oldImageName = $product->image;
            $imageName = substr($oldImageName, 0, strrpos($oldImageName, ".")) . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
        }
       $input =  $product->updateOrFail($request->all());
        if($input){

            return $this->handleResponse($input, 'Product has been updated successfully!');
        } else {
        return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }
      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    function destroy(Product $product){

        $imageName = $product->image;
        $delete = $product->deleteOrFail();

        if($delete){

            if (File::exists(public_path('products/' . $imageName))) {
                File::delete(public_path('products/' . $imageName));
            }
            return $this->handleResponse($delete,'Product has been deleted successfully!');
        } else {
        return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }

      }


}
