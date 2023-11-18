<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StoreController extends Controller {
    /**
     * Get user store
     */
    public function mystore() {
        $store = Store::where("user_id", auth()->user()->id)
            ->with("store_banners", "store_categories")
            ->first();

        if (!$store) {
            return $this->responseFailed("Not Found", 404, "User store not found");
        }

        return $this->responseSuccess($store);
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $stores = Store::with("store_banners")->get();

        if (!$stores) {
            return $this->responseFailed("Not Found", 404, "Stores not found");
        }

        return $this->responseSuccess($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:stores'],
            'phone_number' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'city_id' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'province_id' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
        ]);

        $newStore =  Store::create([
            ...$validatedData,
            "status" => "on_review",
            'image' => env("DEFAULT_STORE_IMAGE", ""),
            'user_id' => auth()->user()->id
        ]);

        User::where("id", auth()->user()->id)->update([
            'role' => 'seller'
        ]);

        return $this->responseSuccess($newStore);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug) {
        $store = Store::where("slug", $slug)->first();
        if (!$store) {
            return $this->responseFailed("Not Found", 404, "Store not found");
        }

        return $this->responseSuccess($store);
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

    /**
     * Get list available province
     */
    public function get_province() {
        $res = Http::withHeaders([
            'key' => 'f26ecb1fd37bc662a35832e653f4a3fa',
        ])->get('https://api.rajaongkir.com/starter/province');

        if ($res->failed()) {
            return $this->responseFailed("Not Found", 500, "List Province not found");
        }

        $data = $res->json();
        $listProvince = $data['rajaongkir']['results'];
        return $this->responseSuccess($listProvince);
    }

    public function get_cities(Request $request) {
        $province_id = $request->query('province_id');

        if (!$province_id) {
            return $this->responseFailed("Invalid params", 500, "No province_id provided");
        }

        $res = Http::withHeaders([
            'key' => 'f26ecb1fd37bc662a35832e653f4a3fa',
        ])->get('https://api.rajaongkir.com/starter/city', [
            'province' => $province_id
        ]);

        if ($res->failed()) {
            return $this->responseFailed("Not Found", 500, "List Cities not found");
        }

        $data = $res->json();
        // dd($data['rajaongkir']['results']);
        $listCity = $data['rajaongkir']['results'];

        return $this->responseSuccess($listCity);
    }
}
