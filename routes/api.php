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
    'prefix' => 'auth'
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::post('/login', [Controllers\AuthController::class, "login"]);
        Route::post(
            '/register',
            [Controllers\AuthController::class, "register"]
        );
    });

    Route::group([
        'middleware' => ['api', 'auth.jwt'],
    ], function () {
        Route::get('/me', [Controllers\AuthController::class, "me"]);
        Route::post('/refresh', [Controllers\AuthController::class, "refresh"]);
        Route::post('/logout', [Controllers\AuthController::class, "logout"]);
    });
});

Route::group([
    "prefix" => "categories"
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/', [Controllers\CategoryController::class, "index"]);
    });
});

Route::group([
    "prefix" => "products"
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/', [Controllers\ProductController::class, "index"]);
    });
});

Route::group([
    "prefix" => "stores"
], function () {
    Route::group([
        'middleware' => ['api', 'auth.jwt'],
    ], function () {
        Route::get(
            '/mystore',
            [Controllers\StoreController::class, "mystore"]
        );
        Route::post(
            '/',
            [Controllers\StoreController::class, "store"]
        );
    });

    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/', [Controllers\StoreController::class, "index"]);
        Route::get(
            '/get_province',
            [Controllers\StoreController::class, "get_province"]
        );
        Route::get(
            '/get_cities',
            [Controllers\StoreController::class, "get_cities"]
        );
        Route::get(
            '/{slug}',
            [Controllers\StoreController::class, "show"]
        );
    });
});
