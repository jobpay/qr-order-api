<?php

namespace App\Layers\Infrastructure\Repository;

use App\Models\Permission\Role;
use Illuminate\Support\Collection;

class RoleRepository
{
    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return Role::get();
    }

    /**
     * @param int $id
     * @return Role|null
     */
    public function find(int $id): ?Role
    {
        return Role::find($id);
    }
}
