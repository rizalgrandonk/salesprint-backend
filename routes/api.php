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
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/{store_slug}/{product_slug}', [Controllers\ProductController::class, "show"]);
    });
});

Route::group([
    "prefix" => "logistic"
], function () {
    Route::group([
        'middleware' => 'api',
    ], function () {
        Route::get('/province', [Controllers\ProvinceController::class, "index"]);

        Route::get('/city', [Controllers\CityController::class, "index"]);

        Route::get(
            '/get_province',
            [Controllers\LogisticController::class, "get_province"]
        );

        Route::get(
            '/get_cities',
            [Controllers\LogisticController::class, "get_cities"]
        );

        Route::post(
            '/cost',
            [Controllers\LogisticController::class, "cost"]
        );
    });
});

Route::group([
    "prefix" => "orders",
    'middleware' => ['api']
], function () {
    Route::post(
        '/notification',
        [Controllers\OrderController::class, "notification"]
    );


    Route::group([
        'middleware' => ['auth.jwt:user'],
    ], function () {
        Route::post('/get_token', [Controllers\OrderController::class, "get_token"]);

        Route::post('/update_transaction_by_token', [Controllers\OrderController::class, "update_transaction_by_token"]);

        Route::get('/user_transactions', [Controllers\OrderController::class, "user_transactions"]);
        Route::get('/user_orders', [Controllers\OrderController::class, "user_orders"]);
    });

    Route::group([
        'middleware' => ['auth.jwt:seller'],
    ], function () {
        Route::get('/store_orders', [Controllers\OrderController::class, "store_orders"]);

        Route::post('/accept_order', [Controllers\OrderController::class, "accept_order"]);
        Route::post('/ship_order', [Controllers\OrderController::class, "ship_order"]);
        Route::post('/delivered_order', [Controllers\OrderController::class, "delivered_order"]);
        Route::post('/cancel_order', [Controllers\OrderController::class, "cancel_order"]);
    });

    Route::group([
        'middleware' => ['auth.jwt:user'],
    ], function () {
        Route::post('/user_complete_order', [Controllers\OrderController::class, "user_complete_order"]);
        Route::post('/user_cancel_order', [Controllers\OrderController::class, "user_cancel_order"]);
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

    // ? PROTECTED (SELLER)
    Route::group([
        'middleware' => ['api', 'auth.jwt:seller'],
    ], function () {
        Route::post(
            '/{store_slug}/products',
            [Controllers\ProductController::class, "store"]
        );
        Route::post(
            '/{store_slug}/products/{product_slug}',
            [Controllers\ProductController::class, "update"]
        );
        Route::delete(
            '/{store_slug}/products/{product_slug}',
            [Controllers\ProductController::class, "destroy"]
        );
        Route::post(
            '/{store_slug}/products/{product_slug}/images',
            [Controllers\ProductController::class, "store_images"]
        );
        Route::delete(
            '/{store_slug}/products/{product_slug}/images',
            [Controllers\ProductController::class, "destroy_image"]
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
        '/products',
        [Controllers\ProductController::class, "paginated"]
    );
    Route::get(
        '/products/recomendation',
        [Controllers\ProductController::class, "paginated_recomendation"]
    );
    Route::get(
        '/stores/{store_slug}/products',
        [Controllers\ProductController::class, "paginated_store_products"]
    );
    Route::get(
        '/products/{store_slug}/{product_slug}/reviews',
        [Controllers\ProductController::class, "paginated_product_reviews"]
    );
    Route::get(
        '/categories',
        [Controllers\CategoryController::class, "paginated"]
    );
    Route::get(
        '/variant-types',
        [Controllers\VariantTypeController::class, "paginated"]
    );

    Route::group(['middleware' => 'auth.jwt:user'], function () {
        Route::get(
            '/orders/user_transactions',
            [Controllers\OrderController::class, "paginated_user_transactions"]
        );
        Route::get(
            '/orders/user_orders',
            [Controllers\OrderController::class, "paginated_user_orders"]
        );
    });

    Route::group(['middleware' => 'auth.jwt:seller'], function () {
        Route::get(
            '/orders/store_orders',
            [Controllers\OrderController::class, "paginated_store_orders"]
        );
    });
});
