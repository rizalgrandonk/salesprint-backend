<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends BaseModel {
    protected $table = 'provinces';

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'province',
        'province_id',
    ];
}
