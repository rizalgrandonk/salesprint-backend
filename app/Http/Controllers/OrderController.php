<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenOrderRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

const TRANSACTION_ORDER_STATUS_MAP = [
    'settlement' => 'PAID',
    'pending' => 'UNPAID',
    'challenge' => 'CANCELED',
    'deny' => 'CANCELED',
    'cancel' => 'CANCELED',
    'expire' => 'CANCELED',
    'error' => 'CANCELED',
];
const TRANSACTION_STATUS_MESSAGE_MAP = [
    'settlement' => 'Pembayaran berhasil',
    'pending' => 'Menunggu pembayaran',
    'challenge' => 'Pembayaran gagal, order ulang atau hubungi customer service',
    'deny' => 'Pembayaran ditolak',
    'cancel' => 'Pembayaran dibatalkan',
    'expire' => 'Melebihi batas waktu pembayaran',
    'error' => 'Error dalam fase pembayaran, order ulang atau hubungi customer service',
];

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function user_transactions(Request $request) {
        $transactions = Transaction::where('user_id', auth()->user()->id)
            ->paramQuery($request->query())
            ->get();

        if (!$transactions) {
            return $this->responseFailed("Not Found", 404, "Transactions not found");
        }

        return $this->responseSuccess($transactions);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated_user_transactions(Request $request) {
        $transactions = Transaction::where('user_id', auth()->user()->id)
            ->getDataTable($request->query());

        if (!$transactions) {
            return $this->responseFailed("Transactions not Found", 404, "Transactions not found");
        }

        return $this->responseSuccess($transactions);
    }
    /**
     * Display a listing of the resource.
     */
    public function user_orders(Request $request) {
        $orders = Order::where('user_id', auth()->user()->id)
            ->paramQuery($request->query())
            ->get();

        if (!$orders) {
            return $this->responseFailed("Not Found", 404, "Orders not found");
        }

        return $this->responseSuccess($orders);
    }

    /**
     * Display a listing of the resource.
     */
    public function paginated_user_orders(Request $request) {
        $orders = Order::where('user_id', auth()->user()->id)
            ->getDataTable($request->query());

        if (!$orders) {
            return $this->responseFailed("Orders not Found", 404, "Orders not found");
        }

        return $this->responseSuccess($orders);
    }


    public function get_token(TokenOrderRequest $request) {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', '');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', '');
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', '');
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', '');
        \Midtrans\Config::$overrideNotifUrl = env('APP_URL', '') . '/apis/orders/notification';

        $validatedData = $request->validated();

        $orderList = [];

        $item_details = [];

        $grossAmount = 0;

        foreach ($validatedData['orders'] as $order) {
            $orderItems = [];
            foreach ($order['items'] as $item) {
                $selectedProduct = Product::where('id', $item['id'])
                    ->where('store_id', $order['store_id'])
                    ->first();
                if (!$selectedProduct) {
                    return $this->responseFailed(
                        "Item " . $item['id'] . " not found",
                        400,
                        "Item " . $item['id'] . " not found"
                    );
                }
                $selectedProductVariant = ProductVariant::where('id', $item['product_variant_id'])
                    ->where('product_id', $selectedProduct->id)
                    ->first();
                if (!$selectedProductVariant) {
                    return $this->responseFailed(
                        "Item variant " . $item['product_variant_id'] . " not found",
                        400,
                        "Item variant " . $item['product_variant_id'] . " not found"
                    );
                }

                array_push($orderItems, [
                    'product' => $selectedProduct,
                    'product_variant' => $selectedProductVariant,
                    'quantity' => $item['quantity']
                ]);
                array_push($item_details, [
                    "id" => $selectedProduct->id,
                    "price" => $selectedProductVariant->price,
                    "quantity" => $item['quantity'],
                    "name" => $selectedProduct->name,
                ]);
                $grossAmount += ($selectedProductVariant->price * $item['quantity']);
            }

            $selectedStore = Store::where('id', $order['store_id'])->first();
            if (!$selectedStore) {
                return $this->responseFailed(
                    "Store " . $order['store_id'] . " not found",
                    400,
                    "Store " . $order['store_id'] . " not found"
                );
            }

            array_push($orderList, [
                'store' => $selectedStore,
                'order_items' => $orderItems,
                'shipping' => $order['shipping']
            ]);

            array_push($item_details, [
                "id" => "DELIVERY_" . rand(),
                "price" => (int) $order['shipping']['delivery_cost'],
                "quantity" => 1,
                "name" =>
                    strtoupper($order['shipping']['shipping_courier'])
                    . " "
                    . $order['shipping']['delivery_service'],
            ]);

            $grossAmount += (int) $order['shipping']['delivery_cost'];
        }

        $customer_details = [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->phone_number,
            'shipping_address' => [
                'first_name' =>
                    $validatedData['shipping_detail']['reciever_name'],
                'phone' =>
                    $validatedData['shipping_detail']['reciever_phone'],
                "address" =>
                    $validatedData['shipping_detail']['delivery_address'],
                "city" =>
                    $validatedData['shipping_detail']['delivery_city'],
                "postal_code" =>
                    $validatedData['shipping_detail']['delivery_postal_code'],
                "country_code" => "IDN"
            ]
        ];

        $serialOrder = Carbon::now()->format("Ymd")
            . Carbon::createMidnightDate()->diffInMilliseconds(Carbon::now());

        $payload = [
            'transaction_details' => [
                'order_id' => $serialOrder,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($payload);

        $createdTransaction = Transaction::create([
            'total' => $grossAmount,
            'serial_order' => $serialOrder,
            'payment_status' => 'pending',
            'status_code' => 201,
            'status_message' => "Pending, Menunggu pembayaran",
            'snap_token' => $snapToken,
            'user_id' => auth()->user()->id,
        ]);


        $orders = [];
        foreach ($orderList as $order) {
            $orderTotal = $order['shipping']['delivery_cost'];

            foreach ($order['order_items'] as $item) {
                $orderTotal += ($item['product_variant']->price * $item['quantity']);
            }

            $createdOrder = Order::create([
                'total' => $orderTotal,
                'order_status' => 'UNPAID',
                'shipping_status' => "NOT_STARTED",
                'shipping_courier' => $order['shipping']['shipping_courier'],
                'delivery_service' => $order['shipping']['delivery_service'],
                'delivery_address' =>
                    $validatedData['shipping_detail']['delivery_address'],
                'reciever_name' =>
                    $validatedData['shipping_detail']['reciever_name'],
                'reciever_phone' =>
                    $validatedData['shipping_detail']['reciever_phone'],
                'delivery_province_id' =>
                    $validatedData['shipping_detail']['delivery_province_id'],
                'delivery_province' =>
                    $validatedData['shipping_detail']['delivery_province'],
                'delivery_city_id' =>
                    $validatedData['shipping_detail']['delivery_city_id'],
                'delivery_city' =>
                    $validatedData['shipping_detail']['delivery_city'],
                'delivery_postal_code' =>
                    $validatedData['shipping_detail']['delivery_postal_code'],
                'delivery_cost' => $order['shipping']['delivery_cost'],
                'user_id' => auth()->user()->id,
                'store_id' => $order['store']->id,
                'transaction_id' => $createdTransaction->id
            ]);

            $order_items = [];

            foreach ($order['order_items'] as $item) {
                $createdOrderItem = OrderItem::create([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['product']->id,
                    'product_variant_id' => $item['product_variant']->id,
                    'order_id' => $createdOrder->id,
                ]);

                array_push($order_items, $createdOrderItem);
            }

            $createdOrder->order_items = $order_items;
            array_push($orders, $createdOrder);
        }

        $createdTransaction->orders = $orders;

        return $this->responseSuccess([
            'token' => $snapToken,
            'transaction' => $createdTransaction
        ]);
    }

    public function update_transaction_by_token(UpdateTransactionRequest $request) {
        $snapToken = $request->query('snap_token');
        if (!$snapToken) {
            return $this->responseFailed("Invalid Param, span_token not found", 400, "Invalid Param, span_token not found");
        }

        $transaction = Transaction::where('snap_token', $snapToken)->first();
        if (!$transaction) {
            return $this->responseFailed("Transaction not found", 400, "Transaction not found");
        }

        $validatedData = $request->validated();

        $validatedData['status_message'] = TRANSACTION_STATUS_MESSAGE_MAP[$validatedData['payment_status']];

        $transaction->update($validatedData);

        if (isset($validatedData['payment_status'])) {
            $orderStatus = TRANSACTION_ORDER_STATUS_MAP[$validatedData['payment_status']];

            $transaction->orders()->update([
                'order_status' => $orderStatus,
                'cancel_reason' => $orderStatus === "CANCELED" ? $validatedData['status_message'] : null
            ]);
        }

        return $this->responseSuccess($transaction);
    }

    public function notification() {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', '');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', '');
        $notif = new \Midtrans\Notification();

        $serial_order = $notif->order_id;

        $payment_status = $notif->transaction_status;

        $orderTransaction = Transaction::where('serial_order', $serial_order)
            ->first();

        if (!$orderTransaction) {
            return $this->responseFailed("Not Found", 404, "Transactions not found");
        }


        if ($notif->transaction_status == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($notif->payment_type == 'credit_card') {
                if ($notif->fraud_status == 'challenge') {
                    $payment_status = 'challenge';
                } else {
                    $payment_status = 'settlement';
                }
            }
        }

        $orderTransaction->update([
            'transaction_id' => $notif->transaction_id,
            'payment_status' => $payment_status,
            'status_code' => $notif->status_code,
            'status_message' => TRANSACTION_STATUS_MESSAGE_MAP[$payment_status],
            'payment_type' => $notif->payment_type,
        ]);

        $orderStatus = TRANSACTION_ORDER_STATUS_MAP[$payment_status];

        $orderTransaction->orders()->update([
            'order_status' => $orderStatus,
            'cancel_reason' => $orderStatus === "CANCELED" ? TRANSACTION_STATUS_MESSAGE_MAP[$payment_status] : null
        ]);

        return $this->responseSuccess([
            'serial_order' => $serial_order,
            'status' => $notif->transaction_status
        ]);
    }
}
