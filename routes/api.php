<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\Artisan;

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

Route::get('/migrate', function () {
    return Artisan::call('migrate:fresh --seed');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [Controllers\AuthController::class, "login"]);
    Route::post('register', [Controllers\AuthController::class, "register"]);
});

Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'auth'
], function () {
    Route::get('me', [Controllers\AuthController::class, "me"]);
    Route::post('refresh', [Controllers\AuthController::class, "refresh"]);
    Route::post('logout', [Controllers\AuthController::class, "logout"]);
});


// PUBLIC ROUTES
Route::group([
    'middleware' => 'api',
], function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [Controllers\CategoryController::class, "index"]);
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [Controllers\ProductController::class, "index"]);
    });

    Route::prefix('stores')->group(function () {
        Route::get('/', [Controllers\StoreController::class, "index"]);
    });
});


// PRIVATE ROUTES
Route::group([
    'middleware' => 'auth.jwt',
    'prefix' => 'store'
], function () {
    Route::get(
        '/mystore',
        [Controllers\StoreController::class, "mystore"]
    );
});
