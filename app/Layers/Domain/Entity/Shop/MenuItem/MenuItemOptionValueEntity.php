<?php

namespace App\Layers\Domain\Entity\Shop\MenuItem;

class MenuItemOptionValueEntity
{
    /**
     * @param int|null $id
     * @param int $order
     * @param string $name
     * @param int $cost
     */
    private function __construct(
        private readonly ?int   $id,
        private readonly int    $order,
        private readonly string $name,
        private readonly int    $cost,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $order
     * @param string $name
     * @param int $cost
     * @return self
     */
    public static function make(
        ?int   $id,
        int    $order,
        string $name,
        int    $cost,
    ): self {
        return new self(
            id: $id,
            order: $order,
            name: $name,
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
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }
}
