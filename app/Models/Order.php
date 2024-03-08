<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Notifications\OrderUpdate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Class Order
 * 
 * @property string $id
 * @property float $total
 * @property string $order_status
 * @property string $order_number
 * @property string $shipping_status
 * @property string $cancel_reason
 * @property string $shipping_tracking_number
 * @property string $shipping_courier
 * @property array $shipping_history
 * @property string $reciever_name
 * @property string $reciever_phone
 * @property string $delivery_province_id
 * @property string $delivery_province
 * @property string $delivery_city_id
 * @property string $delivery_city
 * @property string $delivery_postal_code
 * @property string $delivery_address
 * @property string $delivery_service
 * @property float $delivery_cost
 * @property string $user_id
 * @property string $store_id
 * @property string $transaction_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Store $store
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Order extends BaseModel {
	protected $table = 'orders';

	protected $casts = [
		'id' => 'string',
		'total' => 'float',
		'transaction_id' => 'string',
		'delivery_cost' => 'float',
		'shipping_history' => 'array',
		'user_id' => 'string',
		'store_id' => 'string',
	];

	protected $fillable = [
		'total',
		'order_number',
		'shipping_days_estimate',
		'accept_deadline',
		'shipping_deadline',
		'deliver_deadline',
		'recieve_deadline',
		'paid_at',
		'accepted_at',
		'shipped_at',
		'delivered_at',
		'completed_at',
		'canceled_at',
		'order_status',
		'shipping_status',
		'cancel_reason',
		'shipping_tracking_number',
		'shipping_courier',
		'shipping_history',
		'reciever_name',
		'reciever_phone',
		'delivery_province_id',
		'delivery_province',
		'delivery_city_id',
		'delivery_city',
		'delivery_postal_code',
		'delivery_address',
		'delivery_service',
		'delivery_cost',
		'user_id',
		'store_id',
		'transaction_id'
	];

	protected static function getTrackInfo(Order $order) {
		$cacheKey = "track_{$order->order_number}_{$order->shipping_tracking_number}";

		$dataCache = Cache::get($cacheKey);
		if (
			$dataCache &&
			isset($dataCache) &&
			isset($dataCache['detail']) &&
			isset($dataCache['detail']['receiver']) &&
			isset($dataCache['summary']) &&
			isset($dataCache['summary']['status'])
		) {
			return $dataCache;
		}

		$res = Http::get(
			env(
				'BINDERBITE_BASE_URL',
				'http://localhost:8800/api'
			) . '/track',
			[
				'api_key' => env('BINDERBITE_API_KEY', ''),
				'awb' => $order->shipping_tracking_number
			]
		);

		if ($res->failed()) {
			return null;
		}

		$data = $res->json();

		if (
			!$data ||
			!isset($data) ||
			!isset($data['detail']) ||
			!isset($data['detail']['receiver']) ||
			!isset($data['summary']) ||
			!isset($data['summary']['status'])
		) {
			return null;
		}

		Cache::put($cacheKey, $data, now()->addMinutes(10));

		return $data;
	}

	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void {
		static::retrieved(function (Order $order) {
			if ($order->order_status !== 'SHIPPED') {
				return;
			}

			$trackInfo = Order::getTrackInfo($order);
			info("trackInfo_{$order->order_number}", $trackInfo);
			if ($trackInfo['summary']['status'] !== 'DELIVERED') {
				return;
			}

			$order->update([
				'order_status' => 'DELIVERED',
				'shipping_status' => 'DELIVERED',
				'recieve_deadline' => Carbon::now()->addDays(2),
				'delivered_at' => isset ($trackInfo['summary']['date']) ? Carbon::createFromFormat('Y-m-d h:i:s', $trackInfo['summary']['date']) : Carbon::now()
			]);
		});

		static::created(function (Order $order) {
			$order->user->notifyNow(new OrderUpdate($order));
			$order->store->user->notifyNow(new OrderUpdate($order));
		});

		static::updated(function (Order $order) {
			$dateNow = Carbon::now()->toFormattedDateString();
			info("Updated {$dateNow} {$order->order_status}", $order->getChanges());
			if ($order->wasChanged('order_status')) {
				$order->user->notifyNow(new OrderUpdate($order));
				$order->store->user->notifyNow(new OrderUpdate($order));
			}
		});
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
	public function store() {
		return $this->belongsTo(Store::class);
	}
	public function transaction() {
		return $this->belongsTo(Transaction::class);
	}

	public function order_items() {
		return $this->hasMany(OrderItem::class);
	}
}
