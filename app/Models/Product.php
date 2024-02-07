<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Support\Facades\DB;

/**
 * Class Product
 * 
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property int $stok
 * @property float $average_rating
 * @property string $category_id
 * @property string $store_id
 * @property string|null $store_category_id
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
class Product extends BaseModel {
	protected $table = 'products';

	protected $casts = [
		'id' => 'string',
		'price' => 'float',
		'stok' => 'int',
		'average_rating' => 'float',
		'category_id' => 'string',
		'store_id' => 'string',
		'store_category_id' => 'string'
	];

	protected $fillable = [
		'name',
		'slug',
		'slug_with_store',
		'description',
		'price',
		'stok',
		'sku',
		'average_rating',
		'weight',
		'length',
		'width',
		'height',
		'category_id',
		'store_id',
		'store_category_id'
	];

	public function category() {
		return $this->belongsTo(Category::class);
	}

	public function store_category() {
		return $this->belongsTo(StoreCategory::class);
	}

	public function store() {
		return $this->belongsTo(Store::class);
	}

	public function order_items() {
		return $this->hasMany(OrderItem::class);
	}

	public function product_images() {
		return $this->hasMany(ProductImage::class);
	}

	public function product_variants() {
		return $this->hasMany(ProductVariant::class);
	}

	public function reviews() {
		return $this->hasMany(Review::class);
	}

	public function variant_options() {
		return $this->hasMany(VariantOption::class);
	}

	function calculateCosineSimilarity($setA, $setB) {
		$dotProduct = 0;
		$magnitudeA = 0;
		$magnitudeB = 0;

		foreach ($setA as $item => $rating) {
			if (isset($setB[$item])) {
				$dotProduct += $rating * $setB[$item];
			}
			$magnitudeA += pow($rating, 2);
		}

		foreach ($setB as $rating) {
			$magnitudeB += pow($rating, 2);
		}

		$magnitudeA = sqrt($magnitudeA);
		$magnitudeB = sqrt($magnitudeB);

		if ($magnitudeA == 0 || $magnitudeB == 0) {
			return 0;
		}

		return $dotProduct / ($magnitudeA * $magnitudeB);
	}

	function calculateSimilarity($userItemMatrix) {
		$similarityMatrix = [];
		$users = array_keys($userItemMatrix);

		foreach ($users as $userA) {
			$similarityMatrix[$userA] = [];

			foreach ($users as $userB) {
				if ($userA !== $userB) {
					$similarity = $this->calculateCosineSimilarity($userItemMatrix[$userA], $userItemMatrix[$userB]);
					$similarityMatrix[$userA][$userB] = $similarity;
				}
			}
		}

		return $similarityMatrix;
	}

	function generateRecommendations($targetUser) {
		$userItemMatrix = Review::select('user_id', 'product_id', 'rating')
			->groupBy('user_id', 'product_id', 'rating') // Include all selected columns in GROUP BY
			->get()
			->groupBy('user_id')
			->map(function ($userReviews) {
				return $userReviews->pluck('rating', 'product_id')->toArray();
			})
			->toArray();

		info("userItemMatrix " . $targetUser);
		info($userItemMatrix);

		$similarityMatrix = $this->calculateSimilarity($userItemMatrix);

		// info("similarityMatrix");
		// info($similarityMatrix);

		$userSimilarities = $similarityMatrix[$targetUser];
		uasort($userSimilarities, function ($a, $b) {
			if ($a == $b) {
				return 0;
			}
			return ($a > $b) ? -1 : 1;
		});

		// info("userSimilarities");
		// info($userSimilarities);

		$nearestNeighbors = array_keys($userSimilarities);
		info("nearestNeighbors" . $targetUser);
		info($nearestNeighbors);

		$recommendations = [];
		$reviewed = [];

		foreach ($nearestNeighbors as $neighbor) {
			$neighborRatings = $userItemMatrix[$neighbor];

			foreach ($neighborRatings as $item => $rating) {
				if (!isset($userItemMatrix[$targetUser][$item]) && !in_array($item, $recommendations)) {
					array_push($recommendations, $item);
				} elseif (isset($userItemMatrix[$targetUser][$item]) && !in_array($item, $reviewed)) {
					array_push($reviewed, $item);
				}
			}
		}

		// // Sort recommendations by predicted rating in descending order
		// usort($recommendations, function ($a, $b) {
		// 	return $b['predicted_rating'] <=> $a['predicted_rating'];
		// });

		return [...$recommendations, ...$reviewed];
	}

	/**
	 * Scope a query to only include records with a certain status.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param array<string mixed> param
	 * @param string userId
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeGetRecomendation($query, $param, $userId = null) {
		if (isset($param['with'])) {
			$with = $param['with'];
			$query->with($with);
		}

		if (isset($param['withCount'])) {
			$withCount = $param['withCount'];
			$query->withCount($withCount);
		}

		if (isset($param['filters'])) {
			$filters = $param['filters'];
			foreach ($filters as $key => $val) {
				list($operator, $value) = explode(';', $val);
				if ($key && $key !== '' && $value && $value !== '' && $operator && $operator !== '') {
					$query->where($key, $operator, $value);
				}
			}
		}

		if (isset($param['search'])) {
			$search = $param['search'];
			$field = array_keys($search)[0];
			$value = $search[$field];

			if ($field && $field !== '' && $value && $value !== '') {
				$query->where($field, 'LIKE', '%' . $value . '%');
			}
		}

		if (!$userId) {
			return $query->orderBy('average_rating', 'desc');
		}

		$cacheKey = 'recomendation_' . $userId;

		// info(Cache::get($cacheKey));

		$ids = Cache::remember(
			$cacheKey,
			now()->addMinutes(2),
			function () use ($userId) {
				return $this->generateRecommendations($userId);
			}
		);

		info($userId, $ids);

		return $query->whereIn('id', $ids)
			->orderByRaw("FIELD(id, " . implode(',', $ids) . ")");
	}
}
