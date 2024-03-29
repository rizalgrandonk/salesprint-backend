<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreBannerRequest;
use App\Http\Requests\UpdateStoreBannerRequest;
use App\Models\Store;
use App\Models\StoreBanner;
use Illuminate\Http\Request;

class StoreBannerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $slug) {
        $store = Store::where("slug", $slug)
            ->paramQuery($request->query())
            ->first();

        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        return $this->responseSuccess($store->store_banners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStoreBannerRequest $request, string $slug) {
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

        $newBanner = $store->store_banners()->create($validatedData);
        return $this->responseSuccess($newBanner, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreBannerRequest $request, string $slug, string $id) {
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

        $banner = $store->store_banners()->where("id", $id)->first();
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

        $banner = StoreBanner::where("id", $id)->first();
        if (!$banner) {
            return $this->responseFailed("Store banner not Found", 404, "Store banner not found");
        }

        if (isset($banner->image) && !str_contains($banner->image, "unsplash")) {
            CloudinaryStorage::delete($banner->image);
        }

        $banner->delete();

        return $this->responseSuccess(['id' => $banner->id], 200, "Data deleted");
    }
}
