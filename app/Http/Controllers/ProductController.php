<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $products =  Product::with('category', 'store_category', 'store', 'product_images', 'product_variants.variant_options.variant_type')->withCount("reviews")->get();

        if (!$products) {
            return $this->responseFailed("Not Found", 404, "Products not found");
        }

        return $this->responseSuccess($products);
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
    public function show(Product $product) {
        if (!$product) {
            return $this->responseFailed("Not Found", 404, "Product not found");
        }

        return $this->responseSuccess($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product) {
        //
    }
}
