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
 * @property string $id
 * @property int $quantity
 * @property string $product_id
 * @property string $product_variant_id
 * @property string $order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property Product $product
 * @property ProductVariant $product_variant
 *
 * @package App\Models
 */
class OrderItem extends BaseModel {
	protected $table = 'order_items';

	protected $casts = [
		'id' => 'string',
		'quantity' => 'int',
		'product_id' => 'string',
		'product_variant_id' => 'string',
		'order_id' => 'string'
	];

	protected $fillable = [
		'quantity',
		'product_id',
		'product_variant_id',
		'order_id'
	];

	public function order() {
		return $this->belongsTo(Order::class);
	}

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function product_variant() {
		return $this->belongsTo(ProductVariant::class);
	}
}
