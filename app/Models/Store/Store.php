<?php

namespace App\Models\Store;

use App\Models\BaseModel;
use App\Models\Menu\Category;
use App\Models\Menu\MenuItem;
use App\Models\Order\Order;
use App\Models\Sale;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property string $name 店舗名
 * @property int|null $category_id 店舗カテゴリ
 * @property string|null $description 店舗説明
 * @property string|null $logo ロゴ
 * @property string|null $postal_code 郵便番号
 * @property string|null $address 住所
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MenuItem> $menuItems
 * @property-read int|null $menu_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Sale> $sales
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Seat> $seats
 * @property-read int|null $seats_count
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends BaseModel
{
    use HasFactory;

    // StoreからCategoriesへの1対多のリレーション
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // StoreからSeatsへの1対多のリレーション
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    // StoreからMenuItemsへの1対多のリレーション
    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'store_id');
    }

    // StoreからOrdersへの1対多のリレーション
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // StoreからSalesへの1対多のリレーション
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
