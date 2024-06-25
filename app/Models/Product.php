<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache as FacadesCache;

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
		'store_category_id' => 'string',
		'order_count' => 'int'
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
	public function orders() {
		return $this->hasManyThrough(Order::class, OrderItem::class);
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

		// ? Menghitung dot product A.B
		foreach ($setA as $item => $rating) {
			if (isset($setB[$item])) {
				$dotProduct += $rating * $setB[$item];
			}
		}

		// ? Menghitung magnitude A
		foreach ($setA as $rating) {
			$magnitudeA += pow($rating, 2);
		}
		$magnitudeA = sqrt($magnitudeA);

		// ? Menghitung magnitude B
		foreach ($setB as $rating) {
			$magnitudeB += pow($rating, 2);
		}
		$magnitudeB = sqrt($magnitudeB);


		if ($magnitudeA == 0 || $magnitudeB == 0) {
			return 0;
		}

		return $dotProduct / ($magnitudeA * $magnitudeB);
	}

	function calculateJaccardSimilarity($setA, $setB) {
		$itemsA = array_keys($setA);
		$itemsB = array_keys($setB);
		$intersectionSize = count(array_intersect($itemsA, $itemsB));
		$unionSize = count(array_unique(array_merge($itemsA, $itemsB)));

		// Avoid division by zero
		if ($unionSize == 0) {
			return 0;
		}

		return $intersectionSize / $unionSize;
	}

	function calculateSimilarity($userItemMatrix, $alg = 'cosine') {
		$similarityMatrix = [];
		$users = array_keys($userItemMatrix);

		foreach ($users as $userA) {
			$similarityMatrix[$userA] = [];

			foreach ($users as $userB) {
				if ($userA !== $userB) {
					$similarity = 0;
					if ($alg === 'jaccard') {
						$similarity = $this->calculateJaccardSimilarity($userItemMatrix[$userA], $userItemMatrix[$userB]);
					} else {
						$similarity = $this->calculateCosineSimilarity($userItemMatrix[$userA], $userItemMatrix[$userB]);
					}
					$similarityMatrix[$userA][$userB] = $similarity;
				}
			}
		}

		return $similarityMatrix;
	}

	function generateRecommendations($targetUser, $alg) {
		$userItemMatrix = Review::select('user_id', 'product_id', 'rating')
			->groupBy('user_id', 'product_id', 'rating') // Include all selected columns in GROUP BY
			->get()
			->groupBy('user_id')
			->map(function ($userReviews) {
				return $userReviews->pluck('rating', 'product_id')->toArray();
			})
			->toArray();

		if (!isset($userItemMatrix[$targetUser])) {
			return null;
		}

		info("userItemMatrix, target user => " . $targetUser);
		info($userItemMatrix);

		$similarityMatrix = $this->calculateSimilarity($userItemMatrix, $alg);

		info("similarityMatrix {$alg} target user => " . $targetUser);
		info($similarityMatrix);

		$userSimilarities = $similarityMatrix[$targetUser];
		uasort($userSimilarities, function ($a, $b) {
			if ($a == $b) {
				return 0;
			}
			return ($a > $b) ? -1 : 1;
		});

		info("userSimilarities {$alg} target user => " . $targetUser);
		info($userSimilarities);

		$nearestNeighbors = array_keys($userSimilarities);
		// info("nearestNeighbors" . $targetUser);
		// info($nearestNeighbors);

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

		$recomendationResult = [...$recommendations, ...$reviewed];

		info("recomendationResult {$alg} target user => " . $targetUser);
		info($recomendationResult);

		return $recomendationResult;
	}

	/**
	 * Scope a query to only include records with a certain status.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param array<string mixed> $param
	 * @param string $userId
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

		$alg = isset($param['alg']) && $param['alg'] === 'jaccard'
			? 'jaccard'
			: 'cosine';

		$cacheKey = "recomendation_{$alg}_{$userId}";

		// info(Cache::get($cacheKey));

		$ids = FacadesCache::remember(
			$cacheKey,
			now()->addMinutes(2),
			function () use ($userId, $alg) {
				return $this->generateRecommendations($userId, $alg);
			}
		);

		if (!$ids) {
			return $query->orderBy('average_rating', 'desc');
		}

		// info($userId, $ids);

		return $query->whereIn('id', $ids)
			->orderByRaw("FIELD(id, " . implode(',', $ids) . ")");
	}
}
