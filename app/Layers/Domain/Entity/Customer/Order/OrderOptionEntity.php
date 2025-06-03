<?php

namespace App\Layers\Domain\Entity\Customer\Order;

class OrderOptionEntity
{
    /**
     * @param int|null $id
     * @param int $option_id
     * @param string $option_name
     * @param int $option_value_id
     * @param string $option_value_name
     * @param int $cost
     */
    private function __construct(
        private readonly ?int $id,
        private readonly int $option_id,
        private readonly string $option_name,
        private readonly int $option_value_id,
        private readonly string $option_value_name,
        private readonly int $cost,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $option_id
     * @param string $option_name
     * @param int $option_value_id
     * @param string $option_value_name
     * @param int $cost
     * @return self
     */
    public static function make(
        ?int $id,
        int $option_id,
        string $option_name,
        int $option_value_id,
        string $option_value_name,
        int $cost,
    ): self {
        return new self(
            id: $id,
            option_id: $option_id,
            option_name: $option_name,
            option_value_id: $option_value_id,
            option_value_name: $option_value_name,
            cost: $cost,
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
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOptionId(): int
    {
        return $this->option_id;
    }

    /**
     * @return string
     */
    public function getOptionName(): string
    {
        return $this->option_name;
    }

    /**
     * @return int
     */
    public function getOptionValueId(): int
    {
        return $this->option_value_id;
    }

    /**
     * @return string
     */
    public function getOptionValueName(): string
    {
        return $this->option_value_name;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }
}
