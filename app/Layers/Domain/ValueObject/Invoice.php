<?php

namespace App\Layers\Domain\ValueObject;

class Invoice
{
    /**
     * @param int $value
     */
    public function __construct(
        private int $value
    ) {
    }

    /**
     * @param int $value
     * @return self
     */
    public static function make(int $value): self
    {
        return new self($value);
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getConsumptionTax(): int
    {
        return (int)($this->value * 0.1);
    }

    /**
     * @return int
     */
    public function getTotalIncludingTax(): int
    {
        return $this->value + $this->getConsumptionTax();
    }
}
