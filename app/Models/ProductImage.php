<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductImage
 * 
 * @property string $id
 * @property string $image_url
 * @property bool $main_image
 * @property string $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class ProductImage extends Model {
	protected $table = 'product_images';

	protected $casts = [
		'id' => 'string',
		'main_image' => 'bool',
		'product_id' => 'string'
	];

	protected $fillable = [
		'image_url',
		'main_image',
		'product_id'
	];

	public function product() {
		return $this->belongsTo(Product::class);
	}
}
