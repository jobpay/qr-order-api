<?php

namespace App\Models;

use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property int $store_id
 * @property string $number 座席番号
 * @property int $order 並び順
 * @property int $status 座席の状態
 * @property string $qr_code QRコード情報
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|Seat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Seat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Seat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Seat extends BaseModel
{
    use HasFactory;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
