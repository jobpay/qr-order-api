<?php

namespace App\Models\Menu;

use App\Models\BaseModel;
use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id
 * @property int $store_id
 * @property int $category_id カテゴリーID
 * @property int $number メニュー番号
 * @property string $name メニュー名
 * @property string|null $description 説明
 * @property string $price 価格
 * @property string|null $image メニュー画像
 * @property int $status ステータス
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Menu\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Menu\MenuItemOption> $menuitemOptions
 * @property-read int|null $menuitem_options_count
 * @property-read Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MenuItem extends BaseModel
{
    use HasFactory;

    // MenuItemからStoreへの逆関係
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // MenuItemからCategoryへの逆関係
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // MenuItemからMenuItemOptionsへの1対多のリレーション
    public function menuitemOptions()
    {
        return $this->hasMany(MenuItemOption::class);
    }
}
