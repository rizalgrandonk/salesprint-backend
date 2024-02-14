<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CreateStoreRequest;
use App\Http\Controllers\CloudinaryStorage;

class StoreController extends Controller {
    /**
     * Get user store
     */
    public function mystore(Request $request) {
        $store = Store::where("user_id", auth()->user()->id)
            ->paramQuery($request->query())
            ->first();

        if (!$store) {
            return $this->responseFailed("User store not found", 404, "User store not found");
        }

        return $this->responseSuccess($store);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $stores = Store::paramQuery($request->query())
            ->get();

        if (!$stores) {
            return $this->responseFailed("Stores not Found", 404, "Stores not found");
        }

        return $this->responseSuccess($stores);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated(Request $request) {
        $stores = Store::getDataTable($request->query());

        if (!$stores) {
            return $this->responseFailed("Stores not Found", 404, "Stores not found");
        }

        return $this->responseSuccess($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStoreRequest $request) {
        $validatedData = $request->validated();

        if (isset($validatedData['image'])) {
            $image = $validatedData['image'];
            $result = CloudinaryStorage::upload(
                $image->getRealPath(),
                $image->getClientOriginalName()
            );
            $validatedData["image"] = $result;
        } else {
            $validatedData["image"] = env("DEFAULT_STORE_IMAGE", null);
        }

        $newStore = Store::create([
            ...$validatedData,
            "status" => "on_review",
            'user_id' => auth()->user()->id,
        ]);

        $user = User::where("id", auth()->user()->id)->first();
        if ($user->role === "user") {
            $user->update([
                'role' => 'seller'
            ]);
        }

        return $this->responseSuccess($newStore, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $slug) {
        $store = Store::where("slug", $slug)
            ->paramQuery($request->query())
            ->first();

        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        return $this->responseSuccess($store);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, string $slug) {
        $store = Store::where("slug", $slug)
            ->first();

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

        $defaultImage = env("DEFAULT_STORE_IMAGE", null);

        if (isset($validatedData['image'])) {
            if ($defaultImage !== null && $store->image !== $defaultImage) {
                $image = $validatedData['image'];
                $result = CloudinaryStorage::replace(
                    $store->image,
                    $image->getRealPath(),
                    $image->getClientOriginalName()
                );
                $validatedData["image"] = $result;
            } else {
                $image = $validatedData['image'];
                $result = CloudinaryStorage::upload(
                    $image->getRealPath(),
                    $image->getClientOriginalName()
                );
                $validatedData["image"] = $result;
            }
        }

        $store->update($validatedData);
        return $this->responseSuccess($store);
    }

    public function update_store_status(Request $request, string $slug) {
        $validatedData = $request->validate([
            'status' => ['required', 'string']
        ]);

        $store = Store::where("slug", $slug)
            ->first();

        if (!$store) {
            return $this->responseFailed("Stores not Found", 404, "Store not found");
        }

        $store->update($validatedData);
        return $this->responseSuccess($store);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug) {
        $store = Store::where("slug", $slug)
            ->first();

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

        $store->delete();

        return $this->responseSuccess(['id' => $store->id], 200, "Data deleted");
    }
}
