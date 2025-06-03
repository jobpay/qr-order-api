<?php

namespace App\Layers\Domain\Entity\Customer\Order;

use Illuminate\Support\Collection;

class OrderEntity
{
    /**
     * @param int|null $id
     * @param int $menu_item_id
     * @param string $menu_name
     * @param int $quantity
     * @param Collection $options
     * @param int $price
     */
    private function __construct(
        private readonly ?int $id,
        private readonly int $menu_item_id,
        private readonly string $menu_name,
        private readonly int $quantity,
        private readonly Collection $options,
        private int $price,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $menu_item_id
     * @param string $menu_name
     * @param int $quantity
     * @param Collection $options
     * @param int $price
     * @return self
     */
    public static function make(
        ?int $id,
        int $menu_item_id,
        string $menu_name,
        int $quantity,
        Collection $options,
        int $price,
    ): self {
        return new self(
            id: $id,
            menu_item_id: $menu_item_id,
            menu_name: $menu_name,
            quantity: $quantity,
            options: $options,
            price: $price,
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
    public function getMenuItemId(): int
    {
        return $this->menu_item_id;
    }

    /**
     * @return string
     */
    public function getMenuName(): string
    {
        return $this->menu_name;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return Collection<int, OrderOptionEntity>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        $this->getOptions()->each(function ($option) {
            /** @var OrderOptionEntity $option */
            $this->price += $option->getCost();
        });

        return $this->getPrice() * $this->getQuantity();
    }
}
