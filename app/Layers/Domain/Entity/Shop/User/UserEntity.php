<?php

namespace App\Layers\Domain\Entity\Shop\User;

use App\Layers\Domain\ValueObject\Password;
use App\Layers\Domain\ValueObject\Role;

class UserEntity
{
    /**
     * @param int|null $id
     * @param string $name
     * @param string $email
     * @param Password $password
     * @param Role $role
     * @param int|null $store_id
     */
    private function __construct(
        private readonly ?int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly Password $password,
        private readonly Role $role,
        private readonly ?int $store_id ,
    ) {
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param string $email
     * @param Password $password
     * @param Role $role
     * @param int|null $store_id
     * @return UserEntity
     */
    public static function make(
        ?int $id,
        string $name,
        string $email,
        Password $password,
        Role $role,
        ?int $store_id = null,
    ): UserEntity {
        return new UserEntity(
            id: $id,
            name: $name,
            email: $email,
            password: $password,
            role: $role,
            store_id: $store_id,
        );
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @return int|null
     */
    public function getStoreId(): ?int
    {
        return $this->store_id;
    }

    /**
     * @param int $store_id
     * @return bool
     */
    public function isOtherStore(int $store_id): bool
    {
        return $this->store_id !== $store_id;
    }
}
