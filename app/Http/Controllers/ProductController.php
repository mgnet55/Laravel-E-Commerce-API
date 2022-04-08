<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{

  function __construct()
  {
       $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
       $this->middleware('permission:product-create', ['only' => ['create','store']]);
       $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
       $this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function index(){

      $products = Product::latest()->paginate(5);

        return $products;
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
      $input = Product::create($request->validated());
      if($input){
        return response()->json([
          'msg'=>'Done',
          'new product' => $input
        ]);
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
          return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    function update(ProductRequest $request, Product $product){



        $product->updateOrFail($request->all());

        if($product){
          return response()->json([
            'msg'=>'Done'
          ]);
        }
      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    function destroy(Product $product){

        $delete = $product->delete();

        if($delete){
          return response()->json([
            'msg'=>'Done'
          ]);
        }
      }


}
