<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $categories = Category::with('products')->get();

        if (!$categories) {
            return $this->responseFailed("Not Found", 404, "Categories not found");
        }

        return $this->responseSuccess($categories);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated(Request $request) {
        $categories = Category::getDataTable($request->query());

        if (!$categories) {
            return $this->responseFailed("Categories not Found", 404, "Categories not found");
        }

        return $this->responseSuccess($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request) {
        $validatedData = $request->validated();

        $image = $validatedData['image'];

        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $result = CloudinaryStorage::upload(
                $image->getRealPath(),
                $image->getClientOriginalName()
            );
            $validatedData["image"] = $result;
        }

        $newCategory = Category::create($validatedData);
        return $this->responseSuccess($newCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $slug) {
        $category = Category::where("slug", $slug)->first();
        if (!$category) {
            return $this->responseFailed("Category not Found", 404, "Category not found");
        }

        $validatedData = $request->validated();

        if (isset($validatedData["image"])) {
            $image = $validatedData['image'];

            if ($image instanceof \Illuminate\Http\UploadedFile) {
                $result = CloudinaryStorage::upload(
                    $image->getRealPath(),
                    $image->getClientOriginalName()
                );
                $validatedData["image"] = $result;

                if (!str_contains($category->image, "unsplash")) {
                    CloudinaryStorage::delete($category->image);
                }
            }
        }

        if (isset($validatedData["slug"]) && $validatedData['slug'] !== $category->slug) {
            $validSlug = $request->validate(['slug' => 'unique:categories']);
            if (isset($validSlug["slug"])) {
                $validatedData['slug'] = $validSlug['slug'];
            }
        }

        $category->update($validatedData);
        return $this->responseSuccess($category, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug) {
        $category = Category::where("slug", $slug)->first();
        if (!$category) {
            return $this->responseFailed("Category not Found", 404, "Category not found");
        }

        if (isset($category->image) && !str_contains($category->image, "unsplash")) {
            CloudinaryStorage::delete($category->image);
        }

        $category->delete();

        return $this->responseSuccess(['id' => $category->id], 200, "Data deleted");
    }
}
