<?php

namespace App\Models\Menu;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property int $menu_item_id
 * @property string $name オプション名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Menu\MenuItem $menuItem
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Menu\MenuItemOptionValue> $menuItemOptionValues
 * @property-read int|null $menu_item_option_values_count
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption whereMenuItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MenuItemOption extends BaseModel
{
    use HasFactory;

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function menuItemOptionValues()
    {
        return $this->hasMany(MenuItemOptionValue::class);
    }
}
