<?php

namespace App\Layers\Domain\Entity\Shop\Order;

use Illuminate\Support\Collection;

class OrderEntityList
{
    /**
     * @param Collection $order_entity_list
     */
    private function __construct(
        private readonly Collection $order_entity_list,
    ) {
    }

    /**
     * @param Collection $order_entity_list
     * @return OrderEntityList
     */
    public static function make(
        Collection $order_entity_list,
    ): OrderEntityList {
        return new self(
            order_entity_list: $order_entity_list,
        );
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->order_entity_list;
    }
}
