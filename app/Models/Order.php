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
 * @property int $id
 * @property float $total
 * @property string $serial_order
 * @property string $transaction_id
 * @property string $status
 * @property string $status_code
 * @property string $payment_type
 * @property string|null $payment_code
 * @property string|null $pdf_url
 * @property string $delivery_address
 * @property string $delivery_service
 * @property float $delivery_cost
 * @property string|null $receipt_number
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'total' => 'float',
		'delivery_cost' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'total',
		'serial_order',
		'transaction_id',
		'status',
		'status_code',
		'payment_type',
		'payment_code',
		'pdf_url',
		'delivery_address',
		'delivery_service',
		'delivery_cost',
		'receipt_number',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}
}
