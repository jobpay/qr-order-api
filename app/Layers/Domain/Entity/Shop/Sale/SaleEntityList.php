<?php

namespace App\Layers\Domain\Entity\Shop\Sale;

use Illuminate\Support\Collection;

class SaleEntityList
{
    /**
     * @param Collection $sale_entity_list
     */
    private function __construct(
        private readonly Collection $sale_entity_list,
    ) {
    }

    /**
     * @param Collection $sale_entity_list
     * @return SaleEntityList
     */
    public static function make(
        Collection $sale_entity_list,
    ): SaleEntityList {
        return new self(
            sale_entity_list: $sale_entity_list,
        );
    }

    /**
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->sale_entity_list;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->get()->sum(function ($item) {
            /** @var SaleEntity $item */
            return $item->getAmount();
        });
    }

    /**
     * @param int $store_id
     * @return bool
     */
    public function containsOtherStore(int $store_id): bool
    {
        return $this->sale_entity_list->contains(function (SaleEntity $sale_entity) use ($store_id) {
            return $sale_entity->isOtherStore($store_id);
        });
    }
}
