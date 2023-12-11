<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreBannerRequest;
use App\Models\Store;
use App\Models\StoreBanner;
use Illuminate\Http\Request;

class StoreBannerController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(string $slug) {
        $store = Store::where("slug", $slug)->with('store_banners')->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        return $this->responseSuccess($store->store_banners);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
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
        $result = CloudinaryStorage::upload(
            $image->getRealPath(),
            $image->getClientOriginalName()
        );
        $validatedData["image"] = $result;

        $newBanner = $store->store_banners()->create($validatedData);
        return $this->responseSuccess($newBanner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StoreBanner $storeBanner) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreBanner $storeBanner) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StoreBanner $storeBanner) {
        //
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

        $banner->delete();

        return $this->responseSuccess(['id' => $banner->id], 200, "Data deleted");
    }
}
