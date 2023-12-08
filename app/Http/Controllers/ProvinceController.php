<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $listProvince = Province::all();

        if (!$listProvince) {
            return $this->responseFailed("List Province not found", 404, "User List Province not found");
        }

        return $this->responseSuccess($listProvince);
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
    public function show(Province $province) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province) {
        //
    }
}
