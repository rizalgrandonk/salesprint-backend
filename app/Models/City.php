<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {
    protected $table = 'cities';

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'province_id',
        'province',
        'city_id',
        'city_name',
        'type',
        'postal_code',
    ];
}
