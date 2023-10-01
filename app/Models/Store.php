<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Store
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $address
 * @property string $status
 * @property string|null $image
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Product[] $products
 * @property Collection|StoreBanner[] $store_banners
 * @property Collection|StoreCategory[] $store_categories
 *
 * @package App\Models
 */
class Store extends Model
{
	protected $table = 'stores';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'name',
		'slug',
		'address',
		'status',
		'image',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function store_banners()
	{
		return $this->hasMany(StoreBanner::class);
	}

	public function store_categories()
	{
		return $this->hasMany(StoreCategory::class);
	}
}
