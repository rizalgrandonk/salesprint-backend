<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreCategory
 * 
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property string $store_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Store $store
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class StoreCategory extends BaseModel {
	protected $table = 'store_categories';

	protected $casts = [
		'id' => 'string',
		'store_id' => 'string'
	];

	protected $fillable = [
		'name',
		'slug',
		'image',
		'store_id'
	];

	public function store() {
		return $this->belongsTo(Store::class);
	}

	public function products() {
		return $this->hasMany(Product::class);
	}
}
