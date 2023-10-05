<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VariantOption
 * 
 * @property string $id
 * @property string $value
 * @property string $product_id
 * @property string $variant_type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property VariantType $variant_type
 * @property Collection|ProductVariant[] $product_variants
 *
 * @package App\Models
 */
class VariantOption extends Model {
	protected $table = 'variant_options';

	protected $casts = [
		'id' => 'string',
		'product_id' => 'string',
		'variant_type_id' => 'string'
	];

	protected $fillable = [
		'value',
		'product_id',
		'variant_type_id'
	];

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function variant_type() {
		return $this->belongsTo(VariantType::class);
	}

	public function product_variants() {
		return $this->belongsToMany(ProductVariant::class)
			->withPivot('id')
			->withTimestamps();
	}
}
