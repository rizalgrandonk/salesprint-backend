<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller {
  public function store_sales(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $yearsParams = $request->query('years');
    $yearsFilter = $yearsParams
      ? is_array($yearsParams)
      ? $yearsParams
      : [$yearsParams]
      : null;
    $years = $yearsFilter ?? [Carbon::now()->year];

    $monthlyData = Order::select(
      DB::raw('YEAR(created_at) as year'),
      DB::raw('MONTH(created_at) as period'),
      DB::raw('SUM(total) as total')
    )
      ->where('store_id', $store->id)
      ->where('order_status', 'COMPLETED')
      ->whereIn(DB::raw('YEAR(created_at)'), $years)
      ->groupBy(
        DB::raw('YEAR(created_at)'),
        DB::raw('MONTH(created_at)')
      )
      ->orderBy('year')
      ->orderBy('period')
      ->get();

    $formattedData = $monthlyData->groupBy('year')->map(function ($items) {
      return [
        'year' => $items->first()->year,
        'data' => $items->map(function ($item) {
          return [
            'period' => (int) $item->period,
            'total' => (int) $item->total,
          ];
        })->all(),
      ];
    })->values()->all();

    return $this->responseSuccess($formattedData);
  }

  public function store_sales_summary(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $currentDate = now();

    // Fetch data for the last two months
    $monthlyData = Order::select(
      DB::raw('YEAR(created_at) as year'),
      DB::raw('MONTH(created_at) as month'),
      DB::raw('SUM(total) as total')
    )
      ->where('store_id', $store->id)
      ->where('order_status', 'COMPLETED')
      ->whereBetween('created_at', [
        $currentDate->copy()->subMonths(1)->startOfMonth(),
        $currentDate->endOfMonth(),
      ])
      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
      ->orderBy('year')
      ->orderBy('month')
      ->get();

    // Calculate the difference between current month and last month
    $currentMonthTotal = $monthlyData->where('month', $currentDate->month)->sum('total');
    $lastMonthTotal = $monthlyData->where('month', $currentDate->subMonth()->month)->sum('total');
    $difference = $currentMonthTotal - $lastMonthTotal;

    // Calculate the percentage difference
    $percentageDifference = ($lastMonthTotal != 0) ? (abs($difference) / $lastMonthTotal) * 100 : 0;

    return $this->responseSuccess([
      'current_month' => $currentMonthTotal,
      'last_month' => $lastMonthTotal,
      'difference' => $difference,
      'percentage_difference' => $percentageDifference,
    ]);
  }

  public function store_order_summary(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $currentDate = now();

    // Fetch data for the last two months
    $monthlyData = Order::select(
      DB::raw('YEAR(created_at) as year'),
      DB::raw('MONTH(created_at) as month'),
      DB::raw('COUNT(*) as count')
    )
      ->where('store_id', $store->id)
      ->whereBetween('created_at', [
        $currentDate->copy()->subMonths(1)->startOfMonth(),
        $currentDate->endOfMonth(),
      ])
      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
      ->orderBy('year')
      ->orderBy('month')
      ->get();

    // Calculate the difference between current month and last month
    $currentMonthTotal = $monthlyData->where('month', $currentDate->month)->sum('count');
    $lastMonthTotal = $monthlyData->where('month', $currentDate->subMonth()->month)->sum('count');
    $difference = $currentMonthTotal - $lastMonthTotal;

    // Calculate the percentage difference
    $percentageDifference = ($lastMonthTotal != 0) ? (abs($difference) / $lastMonthTotal) * 100 : 0;

    return $this->responseSuccess([
      'current_month' => $currentMonthTotal,
      'last_month' => $lastMonthTotal,
      'difference' => $difference,
      'percentage_difference' => $percentageDifference,
    ]);
  }

  public function store_rating_summary(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $currentDate = now();

    // Fetch average rating excluding the current month
    $lastMonthAverage = Store::select(
      'stores.name as store_name',
      DB::raw('AVG(reviews.rating) as average_rating')
    )
      ->leftJoin('products', 'stores.id', '=', 'products.store_id')
      ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
      ->where('stores.id', $store->id)
      ->whereRaw(
        'YEAR(reviews.created_at) * 12 + MONTH(reviews.created_at) < ?',
        [$currentDate->year * 12 + $currentDate->subMonth()->month]
      )
      ->groupBy('store_name')
      ->first()->average_rating;

    // Fetch average rating including the current month
    $currentMonthAverage = Store::select(
      'stores.name as store_name',
      DB::raw('AVG(reviews.rating) as average_rating')
    )
      ->leftJoin('products', 'stores.id', '=', 'products.store_id')
      ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
      ->where('stores.id', $store->id)
      ->whereRaw(
        'YEAR(reviews.created_at) * 12 + MONTH(reviews.created_at) <= ?',
        [$currentDate->year * 12 + $currentDate->month]
      )
      ->groupBy('store_name')
      ->first()->average_rating;

    $difference = (float) $currentMonthAverage - (float) $lastMonthAverage;

    // Calculate the percentage difference
    $percentageDifference = ((float) $lastMonthAverage != 0) ? (abs($difference) / $lastMonthAverage) * 100 : 0;

    return $this->responseSuccess([
      'current_month' => (float) $currentMonthAverage,
      'last_month' => (float) $lastMonthAverage,
      'difference' => $difference,
      'percentage_difference' => $percentageDifference,
    ]);
  }

  public function store_order_status_count(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $topProvince = Order::select(
      'order_status',
      DB::raw('COUNT(*) as count')
    )
      ->where('store_id', $store->id)
      ->groupBy('order_status')
      ->orderBy('count', 'desc')
      ->limit(10)
      ->get();

    return $this->responseSuccess($topProvince);
  }

  public function store_order_top_province(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $topProvince = Order::select(
      'delivery_province',
      DB::raw('COUNT(*) as count')
    )
      ->where('store_id', $store->id)
      ->groupBy('delivery_province')
      ->orderBy('delivery_province')
      // ->limit(10)
      ->get();

    return $this->responseSuccess($topProvince);
  }

  public function store_order_top_customers(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $topCustomers = Order::select(
      'users.name as user_name',
      'users.image as user_image', // Include the image column
      DB::raw('SUM(total) as total_orders'),
      DB::raw('COUNT(*) as order_count')
    )
      ->join('users', 'orders.user_id', '=', 'users.id')
      ->where('orders.store_id', $store->id)
      ->groupBy('user_name', 'user_image')
      ->orderBy('total_orders', 'desc')
      ->limit(5)
      ->get();

    return $this->responseSuccess($topCustomers);
  }

  public function store_order_top_methods(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $topCustomers = Order::select(
      'transactions.payment_type as payment_type',
      DB::raw('COUNT(*) as count')
    )
      ->join('transactions', 'orders.user_id', '=', 'transactions.id')
      ->where('orders.store_id', $store->id)
      ->groupBy('payment_type')
      ->orderBy('count', 'desc')
      ->limit(5)
      ->get();

    return $this->responseSuccess($topCustomers);
  }

  public function store_order_top_products(Request $request) {
    $store = $store = Store::where('user_id', auth()->user()->id)->first();
    if (!$store) {
      return $this->responseFailed("Not Found", 404, "Store not found");
    }

    $topProducts = Product::where('store_id', $store->id)
      ->with(['product_images'])
      ->withCount([
        'order_items as total_orders' => function ($query) {
          $query->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(\DB::raw('SUM(orders.total)'));
        },
        'order_items as order_count' => function ($query) {
          $query->select(\DB::raw('sum(quantity)'));
        },
      ])
      ->orderBy('total_orders', 'desc')
      ->limit(5)
      ->get();


    return $this->responseSuccess($topProducts);
  }

  // ? ADMIN
  public function admin_stores_count(Request $request) {
    $count = Store::count();

    if ($count === null) {
      return $this->responseFailed(
        "Failed get stores count",
        404,
        "Failed get stores count"
      );
    }

    return $this->responseSuccess([
      'count' => $count
    ]);
  }
  public function admin_products_count(Request $request) {
    $count = Product::count();

    if ($count === null) {
      return $this->responseFailed(
        "Failed get products count",
        404,
        "Failed get products count"
      );
    }

    return $this->responseSuccess([
      'count' => $count
    ]);
  }

  public function admin_users_count(Request $request) {
    $count = User::count();

    if ($count === null) {
      return $this->responseFailed(
        "Failed get users count",
        404,
        "Failed get users count"
      );
    }

    return $this->responseSuccess([
      'count' => $count
    ]);
  }

  public function admin_categories_count(Request $request) {
    $count = Category::count();

    if ($count === null) {
      return $this->responseFailed(
        "Failed get categories count",
        404,
        "Failed get categories count"
      );
    }

    return $this->responseSuccess([
      'count' => $count
    ]);
  }

  public function admin_order_top_products(Request $request) {

    $topProducts = Product::with(['product_images'])
      ->withCount([
        'order_items as total_orders' => function ($query) {
          $query->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(\DB::raw('SUM(orders.total)'));
        },
        'order_items as order_count' => function ($query) {
          $query->select(\DB::raw('sum(quantity)'));
        },
      ])
      ->orderBy('total_orders', 'desc')
      ->limit(5)
      ->get();


    return $this->responseSuccess($topProducts);
  }

  public function admin_order_top_stores(Request $request) {

    $topProducts = Store::withCount([
      'orders as total_orders' => function ($query) {
        $query->select(\DB::raw('sum(total)'));
      },
      'orders as order_count' => function ($query) {
        $query->select(\DB::raw('count(*)'));
      },
    ])
      ->orderBy('total_orders', 'desc')
      ->limit(5)
      ->get();


    return $this->responseSuccess($topProducts);
  }
  public function admin_review_top_products(Request $request) {

    $topProducts = Product::with(['product_images'])
      ->withCount(['reviews'])
      ->orderBy('reviews_count', 'desc')
      ->limit(5)
      ->get();


    return $this->responseSuccess($topProducts);
  }

  public function admin_review_top_stores(Request $request) {

    $topProducts = Store::withCount(['reviews'])
      ->withAvg('reviews', 'rating')
      ->orderBy('reviews_count', 'desc')
      ->limit(5)
      ->get();


    return $this->responseSuccess($topProducts);
  }
}