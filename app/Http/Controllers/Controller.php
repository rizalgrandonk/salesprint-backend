<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function responseSuccess($data, int $status = 200) {
        return response()->json($data, $status);
    }

    public function responseFailed(string $error = "Request failed", int $status = 400, string $message) {
        return response()->json([
            "error" => $error,
            "message" => $message ?? $error
        ], $status);
    }
}
