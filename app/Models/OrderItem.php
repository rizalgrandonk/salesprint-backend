<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 * 
 * @property int $id
 * @property int $quantity
 * @property int $product_id
 * @property int $product_variant_id
 * @property int $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property Product $product
 * @property ProductVariant $product_variant
 *
 * @package App\Models
 */
class OrderItem extends Model
{
	protected $table = 'order_items';

	protected $casts = [
		'quantity' => 'int',
		'product_id' => 'int',
		'product_variant_id' => 'int',
		'order_id' => 'int'
	];

	protected $fillable = [
		'quantity',
		'product_id',
		'product_variant_id',
		'order_id'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public function product_variant()
	{
		return $this->belongsTo(ProductVariant::class);
	}
}
