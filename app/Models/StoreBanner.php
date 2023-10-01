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
 * @property int $id
 * @property string|null $description
 * @property string $image
 * @property int $store_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Store $store
 *
 * @package App\Models
 */
class StoreBanner extends Model
{
	protected $table = 'store_banners';

	protected $casts = [
		'store_id' => 'int'
	];

	protected $fillable = [
		'description',
		'image',
		'store_id'
	];

	public function store()
	{
		return $this->belongsTo(Store::class);
	}
}
