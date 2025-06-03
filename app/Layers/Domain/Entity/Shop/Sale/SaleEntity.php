<?php

namespace App\Layers\Domain\Entity\Shop\Sale;

use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemEntity;
use App\Layers\Domain\Entity\Shop\Order\OrderEntity;
use Carbon\Carbon;

class SaleEntity
{
    /**
     * @param int|null $id
     * @param int $store_id
     * @param OrderEntity $order
     * @param MenuItemEntity $menu_item
     * @param Carbon|null $paid_at
     */
    private function __construct(
        private readonly ?int $id,
        private readonly int $store_id,
        private readonly OrderEntity $order,
        private readonly MenuItemEntity $menu_item,
        private readonly ?Carbon $paid_at,
        private readonly int $amount,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $store_id
     * @param OrderEntity $order
     * @param MenuItemEntity $menu_item
     * @param Carbon|null $paid_at
     * @param int $amount
     * @return self
     */
    public static function make(
        ?int $id,
        int $store_id,
        OrderEntity $order,
        MenuItemEntity $menu_item,
        ?Carbon $paid_at,
        int $amount,
    ): self {
        return new self(
            $id,
            $store_id,
            $order,
            $menu_item,
            $paid_at,
            $amount,
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * @return OrderEntity
     */
    public function getOrder(): OrderEntity
    {
        return $this->order;
    }

    /**
     * @return MenuItemEntity
     */
    public function getMenuItem(): MenuItemEntity
    {
        return $this->menu_item;
    }

    /**
     * @return Carbon|null
     */
    public function getPaidAt(): ?Carbon
    {
        return $this->paid_at;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
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
