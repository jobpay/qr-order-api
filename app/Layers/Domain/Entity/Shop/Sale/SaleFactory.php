<?php

namespace App\Layers\Domain\Entity\Shop\Sale;

use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Domain\Entity\Shop\Order\OrderFactory;
use App\Models\Order\Order;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

class SaleFactory
{
    /**
     * @param OrderFactory $order_factory
     * @param MenuItemFactory $menu_item_factory
     */
    public function __construct(
        private readonly OrderFactory $order_factory,
        private readonly MenuItemFactory $menu_item_factory,
    ) {
    }

    /**
     * @param Collection<int, Order> $order_collection
     * @return SaleEntityList
     */
    public function makeListForCreate(
        Collection $order_collection
    ): SaleEntityList {
        $sale_entities = $order_collection->map(function (Order $model) {
            $menu_item = $model->menuItem;

            return SaleEntity::make(
                id: null,
                store_id: $menu_item->store_id,
                order: $this->order_factory->makeByModel($model),
                menu_item: $this->menu_item_factory->makeByModel($menu_item),
                paid_at: null,
                amount: (int)((int)$model->price * (int)$model->quantity),
            );
        });

        return SaleEntityList::make($sale_entities);

    }

    /**
     * @param Sale $model
     * @return SaleEntity
     * @throws \App\Exceptions\DomainException
     */
    public function makeByModel(Sale $model): SaleEntity
    {
        return SaleEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            order: $this->order_factory->makeByModel($model->order),
            menu_item: $this->menu_item_factory->makeByModel($model->order->menuItem),
            paid_at: $model->paid_at,
            amount: (int)$model->amount,
        );
    }

    //    /**
    //     * @param SaleEntity $sale_entity
    //     * @param UpdateRequest $request
    //     * @return SaleEntity
    //     */
    //    public function makeUpdate(SaleEntity $sale_entity, UpdateRequest $request): SaleEntity
    //    {
    //        return SaleEntity::make(
    //            id: $sale_entity->getId(),
    //            store_id: $sale_entity->getStoreId(),
    //            menu_item_name: $sale_entity->getMenuItemName(),
    //            category_name: $sale_entity->getCategoryName(),
    //            seat_number: $sale_entity->getSeatNumber(),
    //            quantity: $sale_entity->getQuantity(),
    //            amount: $request->input('amount'),
    //        );
    //    }

    /**
     * @param $sale_db_collection
     * @return SaleEntityList
     * @throws \App\Exceptions\DomainException
     */
    public function makeListFromDbCollection($sale_db_collection): SaleEntityList
    {
        $sale_entity_collection = $sale_db_collection->map(function ($sale_model) {
            return $this->makeByModel($sale_model);
        });

        return SaleEntityList::make($sale_entity_collection);
    }
}
