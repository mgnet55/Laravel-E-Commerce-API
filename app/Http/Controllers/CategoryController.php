<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class CategoryController extends ApiResponse
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return $this->handleResponse($categories, 'categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return Response
     */

    public function store(CategoryRequest $request)
    {
        $imageName = 'cat_' . time() . '.' . $request->image->extension();
        $request->image->move(public_path('categories'), $imageName);
        $category = Category::firstOrCreate([...$request->except('image'), 'image' => $imageName]);
        if ($category) {
            return $this->handleResponse([], 'Created Successuly');
        }else{
            return $this->handleError('Failed.', ['Category not added'], 402);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function show(Category $category)
    {
        return $this->handleResponse($category, 'Category');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(CategoryRequest $request, Category $category)
    {

        if ($request->hasFile('image')) {
            $oldImageName = $category->image;
            $imageName = substr($oldImageName, 0, strrpos($oldImageName, ".")) . '.' . $request->image->extension();
            $request->image->move(public_path('categories'), $imageName);
        }
        return $category->updateOrFail($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Category $category)
    {
        $imageName = $category->image;
        if ($category->deleteOrFail()) {
            if (File::exists(public_path('categories/' . $imageName))) {
                File::delete(public_path('categories/' . $imageName));
            }
            return true;
        }
        return false;
    }
}
