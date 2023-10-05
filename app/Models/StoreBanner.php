<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreBanner
 * 
 * @property string $id
 * @property string|null $description
 * @property string $image
 * @property string $store_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Store $store
 *
 * @package App\Models
 */
class StoreBanner extends Model {
	protected $table = 'store_banners';

	protected $casts = [
		'id' => 'string',
		'store_id' => 'string'
	];

	protected $fillable = [
		'description',
		'image',
		'store_id'
	];

	public function store() {
		return $this->belongsTo(Store::class);
	}
}
