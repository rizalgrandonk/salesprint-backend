<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Category extends Model {
	protected $table = 'categories';

	protected $casts = [
		'id' => 'string',
	];

	protected $fillable = [
		'name',
		'slug',
		'image'
	];

	public function products() {
		return $this->hasMany(Product::class);
	}
}
