<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends BaseModel {
    protected $table = 'districts';

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'province_id',
        'province',
        'city_id',
        'city_name',
        'district_id',
        'district_name',
    ];
}
