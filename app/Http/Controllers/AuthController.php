<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderUpdate;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller {

    public function register(RegisterRequest $request) {
        $validatedData = $request->validated();

        $newUser = User::create([
            'image' => env("DEFAULT_USER_IMAGE", ""),
            'role' => 'user',
            ...$validatedData,
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = JWTAuth::fromUser($newUser);

        return $this->respondWithTokenAndUser($token, $newUser);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login() {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->responseFailed("Unauthorize", 403);
        }

        return $this->responseSuccess(
            $this->respondWithTokenAndUser($token, auth()->user())
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me() {
        // $user = User::find(auth()->user()->id);
        // $order = Order::where('order_number', '2024030610856431115297')->first();
        // $user->notifyNow(new OrderUpdate($order));
        // $order->user->notifyNow(new OrderUpdate($order));
        // $order->store->user->notifyNow(new OrderUpdate($order));

        return $this->responseSuccess(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return $this->responseSuccess(null, 200, 'Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->responseSuccess(
            $this->respondWithTokenAndUser(auth()->refresh(), auth()->user())
        );
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithTokenAndUser($token, $user) {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ];
    }
}
