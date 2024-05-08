<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Withdraw
 * 
 * @property string $id
 * @property float $total_amount
 * @property string $bank_code
 * @property string $bank_name
 * @property string $bank_account_number
 * @property string $bank_account_name
 * @property string $status
 * @property string $denied_reason
 * @property string $receipt
 * @property string $store_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Store $store
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Withdraw extends BaseModel {
    protected $table = 'withdraws';

    protected $casts = [
        'id' => 'string',
        'total_amount' => 'float',
        'store_id' => 'string',
    ];

    protected $fillable = [
        'total_amount',
        'bank_code',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'denied_reason',
        'receipt',
        'store_id'
    ];

    public function store() {
        return $this->belongsTo(Store::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
