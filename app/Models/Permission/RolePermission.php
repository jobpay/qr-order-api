<?php

namespace App\Models\Permission;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id
 * @property int $role_id 役割ID
 * @property int $permission_id 権限ID
 * @property string|null $memo メモ
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RolePermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RolePermission extends BaseModel
{
    use HasFactory;
}
