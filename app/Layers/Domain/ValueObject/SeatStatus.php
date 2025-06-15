<?php

namespace App\Layers\Domain\ValueObject;

use App\Exceptions\DomainException;

class SeatStatus
{
    public const VACANT = 0;
    public const ORDER_WAIT = 1;
    public const CUSTOMER_WAIT = 2;
    public const PROVIDED = 3;
    public const CHECKOUT_WAIT = 4;

    /**
     * @param int|null $value
     * @throws DomainException
     */
    public function __construct(
        private ?int $value
    ) {
        $value ??= 0;
        if (!in_array($value, [self::VACANT, self::ORDER_WAIT, self::CUSTOMER_WAIT, self::PROVIDED, self::CHECKOUT_WAIT])) {
            throw new DomainException('座席ステータスの値が不正です');
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
        return match ($this->value) {
            self::VACANT => '空席',
            self::ORDER_WAIT => '注文待ち',
            self::CUSTOMER_WAIT => '提供待ち',
            self::PROVIDED => '提供済み',
            self::CHECKOUT_WAIT => 'チェックアウト待ち',
        };
    }

    /**
     * @return bool
     */
    public function isSession(): bool
    {
        return match ($this->value) {
            self::VACANT => false,
            default => true,
        };
    }
}
