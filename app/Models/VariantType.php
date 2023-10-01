<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class VariantType
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|VariantOption[] $variant_options
 *
 * @package App\Models
 */
class VariantType extends Model
{
	protected $table = 'variant_types';

	protected $fillable = [
		'name'
	];

	public function variant_options()
	{
		return $this->hasMany(VariantOption::class);
	}
}
