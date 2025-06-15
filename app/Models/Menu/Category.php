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
 * @property string $name カテゴリー名
 * @property int $order 並び順
 * @property string|null $description 説明
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Menu\MenuItem> $menuItems
 * @property-read int|null $menu_items_count
 * @property-read Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends BaseModel
{
    use HasFactory;

    // CategoryからStoreへの逆関係
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // CategoryからMenuItemsへの1対多のリレーション
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
