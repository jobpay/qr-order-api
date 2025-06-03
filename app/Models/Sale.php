<?php

namespace App\Models;

use App\Models\Order\Order;
use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property int $store_id
 * @property int $order_id
 * @property string $amount 決済額
 * @property \Illuminate\Support\Carbon $paid_at 決済日時
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Order $order
 * @property-read Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sale whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sale extends BaseModel
{
    use HasFactory;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    protected $casts = [
        'paid_at' => 'datetime',
    ];
}
