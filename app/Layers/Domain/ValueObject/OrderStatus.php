<?php

namespace App\Layers\Domain\ValueObject;

use App\Exceptions\DomainException;

class OrderStatus
{
    private const WAIT = 0;
    private const COMPLETED = 1;
    private const CANCEL = 2;
    private string $name;

    private function __construct(
        private readonly ?int $value,
    ) {
        $this->name = match ($value) {
            self::WAIT => '提供待ち',
            self::COMPLETED => '提供済み',
            self::CANCEL => '取り消し',
            default => throw new DomainException(['OrderStatusの値が不正です。']),
        };
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
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
