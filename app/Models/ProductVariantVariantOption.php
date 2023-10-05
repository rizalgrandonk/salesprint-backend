<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductVariantVariantOption
 * 
 * @property string $id
 * @property string $product_variant_id
 * @property string $variant_option_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ProductVariant $product_variant
 * @property VariantOption $variant_option
 *
 * @package App\Models
 */
class ProductVariantVariantOption extends Model {
	protected $table = 'product_variant_variant_option';

	protected $casts = [
		'id' => 'string',
		'product_variant_id' => 'string',
		'variant_option_id' => 'string'
	];

	protected $fillable = [
		'product_variant_id',
		'variant_option_id'
	];

	public function product_variant() {
		return $this->belongsTo(ProductVariant::class);
	}

	public function variant_option() {
		return $this->belongsTo(VariantOption::class);
	}
}
