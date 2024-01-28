<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVariantTypeRequest;
use App\Http\Requests\UpdateVariantTypeRequest;
use App\Models\VariantType;
use Illuminate\Http\Request;

class VariantTypeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $list = VariantType::paramQuery($request->query())
            ->get();

        if (!$list) {
            return $this->responseFailed("Not Found", 404, "Variant types not found");
        }

        return $this->responseSuccess($list);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated(Request $request) {
        $list = VariantType::getDataTable($request->query());

        if (!$list) {
            return $this->responseFailed("Variant types not Found", 404, "Variant types not found");
        }

        return $this->responseSuccess($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVariantTypeRequest $request) {
        $validatedData = $request->validated();

        $newItem = VariantType::create($validatedData);
        return $this->responseSuccess($newItem, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(VariantType $variantType) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariantTypeRequest $request, string $id) {
        $variantType = VariantType::where("id", $id)->first();
        if (!$variantType) {
            return $this->responseFailed("Variant type not Found", 404, "Variant type not found");
        }

        $validatedData = $request->validated();

        $variantType->update($validatedData);
        return $this->responseSuccess($variantType, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $variantType = VariantType::where("id", $id)->first();
        if (!$variantType) {
            return $this->responseFailed("Variant type not Found", 404, "Variant type not found");
        }

        $variantType->delete();

        return $this->responseSuccess(['id' => $variantType->id], 200, "Data deleted");
    }
}
