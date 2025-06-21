<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Shop\ShopEntity;
use App\Layers\Domain\Entity\Shop\User\UserEntity;
use App\Models\Store\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StoreRepository
{
    /**
     * @param ShopEntity $store_entity
     * @param UserEntity $user_entity
     * @param string|null $image_url
     * @return User
     * @throws \Exception
     */
    public function createWithUser(
        ShopEntity $store_entity,
        UserEntity $user_entity,
        ?string $image_url = null,
    ): User {
        DB::beginTransaction();

        try {
            $store = Store::create([
                'name' => $store_entity->getName(),
                'address' => $store_entity->getAddress(),
                'postal_code' => $store_entity->getPostalCode(),
                'category_id' => $store_entity->getCategoryId(),
                'description' => $store_entity->getDescription(),
                'logo' => $image_url,
            ]);

            $user = User::create([
                'name' => $user_entity->getName(),
                'email' => $user_entity->getEmail(),
                'password' => $user_entity->getPassword()->asHashed(),
                'store_id' => $store->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $user;
    }

    /**
     * @param int $store_id
     * @return Store|null
     */
    public function find(int $store_id): ?Store
    {
        return Store::find($store_id);
    }

    /**
     * @param ShopEntity $store_entity
     * @param string|null $image_url
     * @return void
     */
    public function update(ShopEntity $store_entity, ?string $image_url): void
    {
        Store::query()->where('id', $store_entity->getId())->update([
            'name' => $store_entity->getName(),
            'address' => $store_entity->getAddress(),
            'logo' => $image_url,
            'postal_code' => $store_entity->getPostalCode(),
            'category_id' => $store_entity->getCategoryId(),
            'description' => $store_entity->getDescription(),
        ]);
    }
}
