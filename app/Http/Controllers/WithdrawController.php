<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Models\Order;
use App\Models\Store;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function store_withdraws(Request $request) {
        $store = Store::where('user_id', auth()->user()->id)->first();
        if (!$store) {
            return $this->responseFailed("Not Found", 404, "Store not found");
        }

        $withdraws = Withdraw::where('store_id', $store->id)
            ->paramQuery($request->query())
            ->get();

        if (!$withdraws) {
            return $this->responseFailed("Not Found", 404, "withdraws not found");
        }

        return $this->responseSuccess($withdraws);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated_store_withdraws(Request $request) {
        $store = Store::where('user_id', auth()->user()->id)->first();
        if (!$store) {
            return $this->responseFailed("Not Found", 404, "Store not found");
        }

        $withdraws = Withdraw::where('store_id', $store->id)
            ->getDataTable($request->query());

        if (!$withdraws) {
            return $this->responseFailed("withdraws not Found", 404, "withdraws not found");
        }

        return $this->responseSuccess($withdraws);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create_store_withdraw(CreateWithdrawRequest $request) {
        $validatedData = $request->validated();

        $store = Store::where('user_id', auth()->user()->id)->first();
        if (!$store) {
            return $this->responseFailed("Not Found", 404, "Store not found");
        }

        $orders = Order::where('store_id', $store->id)->where('withdraw_id', null)->get();

        if (!$orders || $orders->count() <= 0) {
            return $this->responseFailed("Not Found", 404, "Order not found");
        }

        $total_amount = $orders->sum('total');

        $newWithdraw = Withdraw::create([
            'total_amount' => $total_amount,
            'bank_code' => isset($validatedData['bank_code'])
                ? $validatedData['bank_code']
                : "unknown",
            'bank_name' => $validatedData['bank_name'],
            'bank_account_number' => $validatedData['bank_account_number'],
            'bank_account_name' => $validatedData['bank_account_name'],
            'status' => 'PENDING',
            'store_id' => $store->id
        ]);

        if (!$newWithdraw) {
            return $this->responseFailed("Failed Create", 404, "Failed create Withdraw");
        }

        // Order::where('store_id', $store->id)
        //     ->where('withdraw_id', null)
        //     ->update([
        //         'withdraw_id' => $newWithdraw->id
        //     ]);
        // $store->update([
        //     'total_balance' => 0
        // ]);

        return $this->responseSuccess($newWithdraw, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdraw $withdraw) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdraw $withdraw) {
        //
    }
}
