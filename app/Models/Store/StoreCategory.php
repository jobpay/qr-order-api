<?php

namespace App\Models\Store;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property string $name カテゴリー名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreCategory extends BaseModel
{
    use HasFactory;
}
