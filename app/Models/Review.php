<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * 
 * @property string $id
 * @property int $rating
 * @property string $coment
 * @property string $user_id
 * @property string $product_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Product $product
 * @property User $user
 *
 * @package App\Models
 */
class Review extends BaseModel {
	protected $table = 'reviews';

	protected $casts = [
		'id' => 'string',
		'rating' => 'int',
		'user_id' => 'string',
		'product_id' => 'string',
		'product_variant_id' => 'string'
	];

	protected $fillable = [
		'rating',
		'coment',
		'user_id',
		'product_id',
		'product_variant_id',
	];

	public function product() {
		return $this->belongsTo(Product::class);
	}

	public function user() {
		return $this->belongsTo(User::class);
	}
}
