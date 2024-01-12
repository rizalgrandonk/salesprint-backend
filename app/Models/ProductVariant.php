<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductVariant
 * 
 * @property string $id
 * @property float $price
 * @property int $stok
 * @property string $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property Collection|OrderItem[] $order_items
 * @property Collection|VariantOption[] $variant_options
 *
 * @package App\Models
 */
class ProductVariant extends BaseModel {
	protected $table = 'product_variants';

	protected $casts = [
		'id' => 'string',
		'price' => 'float',
		'stok' => 'int',
		'product_id' => 'string'
	];

	protected $fillable = [
		'price',
		'stok',
		'sku',
		'product_id'
	];

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function order_items() {
		return $this->hasMany(OrderItem::class);
	}

	public function variant_options() {
		return $this->belongsToMany(VariantOption::class)
			->withPivot('id')
			->withTimestamps();
	}
}
