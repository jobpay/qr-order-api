<?php

namespace App\Layers\Domain\Entity\Shop\User;

use App\Layers\Domain\ValueObject\Password;
use App\Layers\Domain\ValueObject\Role;
use App\Layers\Presentation\Requests\Shop\User\UpdateRequest;
use Illuminate\Support\Collection;

class UserFactory
{
    /**
     * 店舗新規作成時に作成する初期ユーザー
     *
     * @param $request
     * @return UserEntity
     * @throws \App\Exceptions\DomainException
     */
    public function makeNewFirst($request): UserEntity
    {
        return UserEntity::make(
            id: null,
            name: $request->name,
            email: $request->email,
            password: Password::make(password: $request->password),
            role: Role::make(Role::ADMIN),
        );
    }

    /**
     * @param $request
     * @param $store_id
     * @return UserEntity
     * @throws \App\Exceptions\DomainException
     */
    public function makeNew($request, $store_id): UserEntity
    {
        return UserEntity::make(
            id: null,
            name: $request->name,
            email: $request->email,
            password: Password::make($request->password),
            role: Role::make($request->role_id),
            store_id: $store_id,
        );
    }

    /**
     * @param $model
     * @return UserEntity
     * @throws \App\Exceptions\DomainException
     */
    public function makeByModel($model): UserEntity
    {
        return UserEntity::make(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            password: Password::make(
                password: $model->password,
                is_hashed: true,
            ),
            role: Role::make($model->role_id),
            store_id: $model->store_id,
        );
    }

    /**
     * @param UserEntity $entity
     * @param UpdateRequest $request
     * @return UserEntity
     * @throws \App\Exceptions\DomainException
     */
    public function makeUpdate(
        UserEntity $entity,
        UpdateRequest $request
    ): UserEntity {
        $request_password = $request->input('password');

        return UserEntity::make(
            id: $entity->getId(),
            name: $request->input('name'),
            email: $request->input('email'),
            password: !is_null($request_password) ?
                Password::make($request_password) : $entity->getPassword(),
            role: Role::make($request->role_id),
            store_id: $entity->getStoreId(),
        );
    }

    /**
     * @param $collection
     * @return Collection
     * @throws \App\Exceptions\DomainException
     */
    public function makeListFromDbCollection($collection): Collection
    {
        return $collection->map(function ($item) {
            $this->makeByModel($item);
        });
    }
}
