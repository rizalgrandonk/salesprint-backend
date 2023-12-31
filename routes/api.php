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

    // ? PROTECTED (ADMIN)
    Route::group([
        'middleware' => ['api', 'auth.jwt:admin'],
    ], function () {
        Route::post(
            '/',
            [Controllers\CategoryController::class, "store"]
        );
        Route::post(
            '/{slug}',
            [Controllers\CategoryController::class, "update"]
        );
        Route::delete(
            '/{slug}',
            [Controllers\CategoryController::class, "destroy"]
        );
    });
});

Route::group([
    "prefix" => "variant-types"
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/', [Controllers\VariantTypeController::class, "index"]);
    });

    // ? PROTECTED (ADMIN)
    Route::group([
        'middleware' => ['api', 'auth.jwt:admin'],
    ], function () {
        Route::post(
            '/{id}',
            [Controllers\VariantTypeController::class, "update"]
        );
        Route::delete(
            '/{id}',
            [Controllers\VariantTypeController::class, "destroy"]
        );
    });

    // ? PROTECTED (ADMIN, SELLER)
    Route::group([
        'middleware' => ['api', 'auth.jwt:admin,seller'],
    ], function () {
        Route::post(
            '/',
            [Controllers\VariantTypeController::class, "store"]
        );
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
    "prefix" => "province"
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/', [Controllers\ProvinceController::class, "index"]);
    });
});

Route::group([
    "prefix" => "city"
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/', [Controllers\CityController::class, "index"]);
    });
});

Route::group([
    "prefix" => "stores"
], function () {
    // ? PROTECTED
    Route::group([
        'middleware' => ['api', 'auth.jwt'],
    ], function () {
        Route::get(
            '/mystore',
            [Controllers\StoreController::class, "mystore"]
        );
    });

    // ? PROTECTED (SELLER, ADMIN)
    Route::group([
        'middleware' => ['api', 'auth.jwt:seller,admin',],
    ], function () {
        Route::post(
            '/',
            [Controllers\StoreController::class, "store"]
        );
        Route::post(
            '/{slug}',
            [Controllers\StoreController::class, "update"]
        );

        Route::post(
            '/{slug}/banners',
            [Controllers\StoreBannerController::class, "store"]
        );
        Route::post(
            '/{slug}/banners/{id}',
            [Controllers\StoreBannerController::class, "update"]
        );

        Route::delete(
            '/{slug}/banners/{id}',
            [Controllers\StoreBannerController::class, "destroy"]
        );

        Route::post(
            '/{slug}/categories',
            [Controllers\StoreCategoryController::class, "store"]
        );
        Route::post(
            '/{slug}/categories/{id}',
            [Controllers\StoreCategoryController::class, "update"]
        );

        Route::delete(
            '/{slug}/categories/{id}',
            [Controllers\StoreCategoryController::class, "destroy"]
        );
    });

    // ? PROTECTED (ADMIN)
    Route::group([
        'middleware' => ['api', 'auth.jwt:admin'],
    ], function () {
        Route::post(
            '/{slug}/update_store_status',
            [Controllers\StoreController::class, "update_store_status"]
        );
    });

    // ? PUBLIC
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
        Route::get(
            '/{slug}/banners',
            [Controllers\StoreBannerController::class, "index"]
        );
        Route::get(
            '/{slug}/categories',
            [Controllers\StoreCategoryController::class, "index"]
        );

        Route::get(
            '/{store_slug}/products',
            [Controllers\ProductController::class, "store_products"]
        );

    });
});

Route::group([
    'prefix' => 'paginated',
    'middleware' => 'api'
], function () {
    Route::get(
        '/stores',
        [Controllers\StoreController::class, "paginated"]
    );
    Route::get(
        '/stores/{store_slug}/products',
        [Controllers\ProductController::class, "paginated_store_products"]
    );
    Route::get(
        '/categories',
        [Controllers\CategoryController::class, "paginated"]
    );
    Route::get(
        '/variant-types',
        [Controllers\VariantTypeController::class, "paginated"]
    );
});
