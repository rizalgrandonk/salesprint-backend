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
 * @property int $id
 * @property int $product_variant_id
 * @property int $variant_option_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ProductVariant $product_variant
 * @property VariantOption $variant_option
 *
 * @package App\Models
 */
class ProductVariantVariantOption extends Model
{
	protected $table = 'product_variant_variant_option';

	protected $casts = [
		'product_variant_id' => 'int',
		'variant_option_id' => 'int'
	];

	protected $fillable = [
		'product_variant_id',
		'variant_option_id'
	];

	public function product_variant()
	{
		return $this->belongsTo(ProductVariant::class);
	}

	public function variant_option()
	{
		return $this->belongsTo(VariantOption::class);
	}
}
