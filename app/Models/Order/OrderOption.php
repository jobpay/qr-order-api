<?php

namespace App\Models\Order;

use App\Models\BaseModel;
use App\Models\Menu\MenuItemOptionValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id
 * @property int $order_id
 * @property int $menu_item_option_value_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read MenuItemOptionValue $menuItemOptionValue
 * @property-read \App\Models\Order\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption whereMenuItemOptionValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderOption extends BaseModel
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItemOptionValue()
    {
        return $this->belongsTo(MenuItemOptionValue::class);
    }
}
