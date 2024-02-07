<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property string $id
 * @property float $total
 * @property string $serial_order
 * @property string $transaction_id
 * @property string $payment_status
 * @property string $status_code
 * @property string $payment_type
 * @property string|null $payment_code
 * @property string|null $snap_token
 * @property string|null $pdf_url
 * @property string $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Transaction extends BaseModel {
    protected $table = 'transactions';

    protected $casts = [
        'id' => 'string',
        'total' => 'float',
        'user_id' => 'string',
    ];

    protected $fillable = [
        'total',
        'serial_order',
        'snap_token',
        'transaction_id',
        'payment_status',
        'status_code',
        'status_message',
        'payment_type',
        'payment_code',
        'pdf_url',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
