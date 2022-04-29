<?php

namespace App\Http\Controllers;

use App\Classes\ImageManager;
use App\Http\Controllers\API\ApiResponse;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Throwable;

class CategoryController extends ApiResponse
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if($request->page)
        {
            $categories = Category::paginate();
            return $this->handleResponse($categories, 'categories');
        }
        $categories = Category::all();
        return $this->handleResponse($categories, 'categories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return JsonResponse
     */

    public function store(CategoryRequest $request)
    {
        $imageName = 'cat_' . time() . '.' . $request->image->extension();
        $request->image->move(public_path('categories'), $imageName);
        $category = Category::firstOrCreate([...$request->except('image'), 'image' => $imageName]);
        if ($category) {
            return $this->handleResponse([], 'Created Successuly');
        } else {
            return $this->handleError('Failed.', ['Category not added'], 402);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return JsonResponse
     */

    public function show(Category $category)
    {
        return $this->handleResponse($category, 'Category');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     * @throws Throwable
     */

    public function update(CategoryRequest $request, Category $category)
    {
        if($request->image)
        {
            $request->file('image')->storeAs('', name: $category->image, options: 'categories');
            if ($category->updateOrFail($request->except('image'))) {
                return $this->handleResponse($category, 'Category updated successfully');
            }
        }

        if ($category->updateOrFail($request->except('image'))) {
            return $this->handleResponse($category, 'Category updated successfully');
        }
        return $this->handleError('Failed', 'Failed to update category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     * @throws Throwable
     */

    public function destroy(Category $category): JsonResponse
    {
        Product::whereCategoryId($category->id)->update(['category_id'=>1]);
        $imageName = $category->image;
        if ($category->deleteOrFail()) {
            if (File::exists(public_path('categories/' . $imageName))) {
                File::delete(public_path('categories/' . $imageName));
            }
            return $this->handleResponse('', 'Category deleted successfully');
        }
        return $this->handleError('Failed', 'Failed to delete Category');
    }
}
