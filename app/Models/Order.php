<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property string $id
 * @property float $total
 * @property string $order_status
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
