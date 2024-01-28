<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreCategoryRequest;
use App\Http\Requests\UpdateStoreCategoryRequest;
use App\Models\Store;
use App\Models\StoreCategory;
use Illuminate\Http\Request;

class StoreCategoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $slug) {
        $store = Store::where("slug", $slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        $store_categories = StoreCategory::where("store_id", $store->id)
            ->paramQuery($request->query())
            ->get();

        return $this->responseSuccess($store_categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStoreCategoryRequest $request, string $slug) {
        $store = Store::where("slug", $slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        if (auth()->user()->role === "seller") {
            $userStore = Store::where("user_id", auth()->user()->id)
                ->first();

            if ($store->id !== $userStore->id) {
                return $this->responseFailed("Store not belongs to current user", 401, "Unauthorize");
            }
        }

        $validatedData = $request->validated();

        $image = $validatedData['image'];

        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $result = CloudinaryStorage::upload(
                $image->getRealPath(),
                $image->getClientOriginalName()
            );
            $validatedData["image"] = $result;
        }

        $newBanner = $store->store_categories()->create($validatedData);
        return $this->responseSuccess($newBanner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StoreCategory $storeCategory) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreCategoryRequest $request, string $slug, string $id) {
        $store = Store::where("slug", $slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        if (auth()->user()->role === "seller") {
            $userStore = Store::where("user_id", auth()->user()->id)
                ->first();

            if ($store->id !== $userStore->id) {
                return $this->responseFailed("Store not belongs to current user", 401, "Unauthorize");
            }
        }

        $banner = $store->store_categories()->where("id", $id)->first();
        if (!$banner) {
            return $this->responseFailed("Store banner not Found", 404, "Store banner not found");
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

                if (!str_contains($banner->image, "unsplash")) {
                    CloudinaryStorage::delete($banner->image);
                }
            }
        }

        $banner->update($validatedData);
        return $this->responseSuccess($banner, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug, string $id) {
        $store = Store::where("slug", $slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        if (auth()->user()->role === "seller") {
            $userStore = Store::where("user_id", auth()->user()->id)
                ->first();

            if ($store->id !== $userStore->id) {
                return $this->responseFailed("Store not belongs to current user", 401, "Unauthorize");
            }
        }

        $category = StoreCategory::where("id", $id)->first();
        if (!$category) {
            return $this->responseFailed("Store category not Found", 404, "Store category not found");
        }

        if (isset($category->image) && !str_contains($category->image, "unsplash")) {
            CloudinaryStorage::delete($category->image);
        }

        $category->delete();

        return $this->responseSuccess(['id' => $category->id], 200, "Data deleted");
    }
}
