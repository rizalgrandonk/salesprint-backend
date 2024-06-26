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
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $address
 * @property string $city
 * @property string $city_id
 * @property string $province
 * @property string $province_id
 * @property string $postal_code
 * @property string $status
 * @property float $total_balance
 * @property string|null $image
 * @property string|null $store_description
 * @property string $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Product[] $products
 * @property Collection|Order[] $orders
 * @property Collection|StoreBanner[] $store_banners
 * @property Collection|StoreCategory[] $store_categories
 *
 * @package App\Models
 */
class Store extends BaseModel {
	protected $table = 'stores';

	protected $casts = [
		'id' => 'string',
		'city_id' => 'string',
		'province_id' => 'string',
		'user_id' => 'string',
		'reviews_avg_rating' => 'float',
		'total_balance' => 'float'
	];

	protected $fillable = [
		'name',
		'slug',
		'phone_number',
		'address',
		'city',
		'city_id',
		'province',
		'province_id',
		'postal_code',
		'status',
		'image',
		'store_description',
		'total_balance',
		'user_id'
	];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function products() {
		return $this->hasMany(Product::class);
	}

	public function store_banners() {
		return $this->hasMany(StoreBanner::class);
	}

	public function store_categories() {
		return $this->hasMany(StoreCategory::class);
	}

	public function orders() {
		return $this->hasMany(Order::class);
	}

	public function order_items() {
		return $this->hasManyThrough(OrderItem::class, Product::class);
	}

	public function reviews() {
		return $this->hasManyThrough(Review::class, Product::class);
	}
}
