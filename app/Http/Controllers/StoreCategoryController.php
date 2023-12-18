<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreCategory;
use Illuminate\Http\Request;

class StoreCategoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(string $slug) {
        $store = Store::where("slug", $slug)->first();
        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        $store_categories = StoreCategory::where("store_id", $store->id)->withCount('products')->get();

        return $this->responseSuccess($store_categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
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
    public function update(Request $request, StoreCategory $storeCategory) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreCategory $storeCategory) {
        //
    }
}
