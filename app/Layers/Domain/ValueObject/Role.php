<?php

namespace App\Layers\Domain\ValueObject;

use App\Exceptions\DomainException;

class Role
{
    public const ADMIN = 1;
    public const EMPLOYEE = 2;
    public const PART_TIMER = 3;

    /**
     * @param int $id
     */
    private function __construct(
        private int $id,
    ) {
    }

    /**
     * @param int $id
     * @return Role
     * @throws DomainException
     */
    public static function make(int $id): Role
    {
        if (!in_array($id, [self::ADMIN, self::EMPLOYEE, self::PART_TIMER], true)) {
            throw new DomainException(['権限の値が不正です。']);
        }

        return new Role($id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return match ($this->id) {
            self::ADMIN => '管理者',
            self::EMPLOYEE => '従業員',
            self::PART_TIMER => 'アルバイト',
            default => '不明',
        };
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return match ($this->id) {
            self::ADMIN => 1,
            self::EMPLOYEE => 2,
            self::PART_TIMER => 3,
            default => 0,
        };
    }

    public function getRoleNameForCustomer(): string
    {
        return match ($this->id) {
            self::ADMIN => '管理者',
            self::EMPLOYEE => '従業員',
            self::PART_TIMER => 'アルバイト',
            default => '不明',
        };
    }
}
