<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $province_id = $request->query('province_id');

        if (!$province_id) {
            return $this->responseFailed("Invalid params", 500, "No province_id provided");
        }

        $listCity = City::where("province_id", $province_id)->get();
        if (!$listCity) {
            return $this->responseFailed("List City not Found", 404, "List City not found");
        }

        return $this->responseSuccess($listCity);
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
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city) {
        //
    }
}
