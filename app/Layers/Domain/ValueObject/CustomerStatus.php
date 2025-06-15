<?php

namespace App\Layers\Domain\ValueObject;

use App\Exceptions\DomainException;

class CustomerStatus
{
    public const EMPTY = 0;
    public const PRESENT = 1;
    public const CLOSED = 2;

    /**
     * @param int|null $value
     * @throws DomainException
     */
    public function __construct(
        private readonly ?int $value
    ) {
        $value = $value ?? self::EMPTY;
        if (!in_array($value, [self::EMPTY, self::PRESENT, self::CLOSED])) {
            throw new DomainException(['座席セッションステータスの値が不正です']);
        }
    }

    /**
     * @param int|null $value
     * @return self
     * @throws DomainException
     */
    public static function make(?int $value = null): self
    {
        return new self($value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return match ($this->value) {
            self::EMPTY => '空席',
            self::PRESENT => '在席中',
            self::CLOSED => '退席済み',
        };
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isPresent(): bool
    {
        return $this->value === self::PRESENT;
    }

    public function isClosed(): bool
    {
        return $this->value === self::CLOSED;
    }
}
