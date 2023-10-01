<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property int $stok
 * @property float $average_rating
 * @property int $category_id
 * @property int $store_id
 * @property int|null $store_category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Category $category
 * @property StoreCategory|null $store_category
 * @property Store $store
 * @property Collection|OrderItem[] $order_items
 * @property Collection|ProductImage[] $product_images
 * @property Collection|ProductVariant[] $product_variants
 * @property Collection|Review[] $reviews
 * @property Collection|VariantOption[] $variant_options
 *
 * @package App\Models
 */
class Product extends Model
{
	protected $table = 'products';

	protected $casts = [
		'price' => 'float',
		'stok' => 'int',
		'average_rating' => 'float',
		'category_id' => 'int',
		'store_id' => 'int',
		'store_category_id' => 'int'
	];

	protected $fillable = [
		'name',
		'slug',
		'description',
		'price',
		'stok',
		'average_rating',
		'category_id',
		'store_id',
		'store_category_id'
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function store_category()
	{
		return $this->belongsTo(StoreCategory::class);
	}

	public function store()
	{
		return $this->belongsTo(Store::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function product_images()
	{
		return $this->hasMany(ProductImage::class);
	}

	public function product_variants()
	{
		return $this->hasMany(ProductVariant::class);
	}

	public function reviews()
	{
		return $this->hasMany(Review::class);
	}

	public function variant_options()
	{
		return $this->hasMany(VariantOption::class);
	}
}
