<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Category::all();
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
        return Category::firstOrCreate([...$request->except('image'), 'image' => $imageName]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Category $category)
    {
        return $category->products;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return Response
     * @throws \Throwable
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
     * @return Response
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