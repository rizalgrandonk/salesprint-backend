<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller {
    /**
     * Get user store
     */
    public function mystore() {
        return Store::where("user_id", auth()->user()->id)->first();
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
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
    public function show(Store $store) {
        return $store;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store) {
        //
    }
}
