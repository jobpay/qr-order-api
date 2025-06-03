<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Shop\User\UserEntity;
use App\Layers\Presentation\Requests\Shop\User\ListRequest;
use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User
    {
        return User::query()->find($id);
    }

    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return Collection
     */
    public function get(ListRequest $request, int $store_id): Collection
    {
        return User::query()
            ->where('store_id', $store_id)
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->orderBy('id')
            ->get();
    }

    /**
     * @param UserEntity $user_entity
     * @return void
     */
    public function create(UserEntity $user_entity): void
    {
        User::create([
            'name' => $user_entity->getName(),
            'email' => $user_entity->getEmail(),
            'password' => $user_entity->getPassword()->asHashed(),
            'store_id' => $user_entity->getStoreId(),
            'role_id' => $user_entity->getRole()->getId(),
        ]);
    }

    /**
     * @param UserEntity $user_entity
     * @return void
     */
    public function update(UserEntity $user_entity): void
    {
        User::query()->where('id', $user_entity->getId())->update([
            'name' => $user_entity->getName(),
            'email' => $user_entity->getEmail(),
            'password' => $user_entity->getPassword()->asHashed(),
            'role_id' => $user_entity->getRole()->getId(),
        ]);
    }

    /**
     * @param UserEntity $user_entity
     * @return void
     */
    public function delete(UserEntity $user_entity): void
    {
        User::query()
            ->where('id', $user_entity->getId())
            ->delete();
    }

    /**
     * @param string $email
     * @param UserEntity $user_entity
     * @return bool
     */
    public function existsEmail(
        string $email,
        UserEntity $user_entity,
    ): bool {
        return User::query()
            ->where('email', $email)
            ->where('store_id', $user_entity->getStoreId())
            ->exists();
    }

    /**
     * @param string $email
     * @param UserEntity $user_entity
     * @return bool
     */
    public function existsEmailAndOtherUser(
        string $email,
        UserEntity $user_entity,
    ): bool {
        return User::query()
            ->where('email', $email)
            ->where('id', '<>', $user_entity->getId())
            ->where('store_id', $user_entity->getStoreId())
            ->exists();
    }
}
