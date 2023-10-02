<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, "login"]);
    Route::post('register', [AuthController::class, "register"]);
});

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'auth'
], function () {
    Route::get('me', [AuthController::class, "me"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::post('logout', [AuthController::class, "logout"]);
});



Route::group([
    'middleware' => 'api',
    'prefix' => 'products'
], function () {
    Route::get('/', [ProductController::class, "index"]);
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'categories'
], function () {
    Route::get('/', [CategoryController::class, "index"]);
});
