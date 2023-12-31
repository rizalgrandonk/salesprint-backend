<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function responseSuccess(
        $data,
        int $status = 200,
        $message = "Request Success"
    ) {
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => $data,
        ], $status);
    }

    public function responseFailed(
        string $message = "Request failed",
        int $status = 400,
        $errors = null,
    ) {
        return response()->json([
            "success" => false,
            "message" => $message,
            "errors" => $errors ?? $message,
        ], $status);
    }
}
