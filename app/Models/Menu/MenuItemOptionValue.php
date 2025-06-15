<?php

namespace App\Models\Menu;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id
 * @property int $menu_item_option_id
 * @property int $order 表示順
 * @property string $value オプション内容
 * @property string|null $cost 追加料金
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Menu\MenuItemOption $menuItemOption
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereMenuItemOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItemOptionValue whereValue($value)
 * @mixin \Eloquent
 */
class MenuItemOptionValue extends BaseModel
{
    use HasFactory;

    public function menuItemOption()
    {
        return $this->belongsTo(MenuItemOption::class);
    }
}
