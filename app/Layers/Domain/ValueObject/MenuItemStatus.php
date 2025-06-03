<?php

namespace App\Layers\Domain\ValueObject;

use App\Exceptions\DomainException;

class MenuItemStatus
{
    private const PREPARATION = 0;
    private const SALE = 1;
    private const SOLD_OUT = 2;
    private string $name;

    private function __construct(
        private readonly ?int $value,
    ) {
        $value ??= 0;
        $this->name = match ($value) {
            self::PREPARATION => '準備中',
            self::SALE => '販売中',
            self::SOLD_OUT => '売り切れ',
            default => throw new DomainException(['MenuItemStatusの値が不正です。']),
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
