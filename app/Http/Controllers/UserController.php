<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
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
    public function show(User $user) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id) {
        if (auth()->user()->role !== "admin" && auth()->user()->id !== $id) {
            return $this->responseFailed("Unauthorized", 403, "Unauthorized");
        }

        $user = User::find($id);

        if (!$user) {
            return $this->responseFailed("User not Found", 404, "User not found");
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

                if (!str_contains($user->image, "unsplash")) {
                    CloudinaryStorage::delete($user->image);
                }
            }
        }

        if (isset($validatedData["username"]) && $validatedData['username'] !== $user->username) {
            $validSlug = $request->validate(['username' => 'unique:users']);
            if (isset($validSlug["username"])) {
                $validatedData['username'] = $validSlug['username'];
            }
        }

        if (isset($validatedData["email"]) && $validatedData['email'] !== $user->email) {
            $validSlug = $request->validate(['email' => 'unique:users']);
            if (isset($validSlug["email"])) {
                $validatedData['email'] = $validSlug['email'];
            }
        }

        $user->update($validatedData);
        return $this->responseSuccess($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user) {
        //
    }
}
