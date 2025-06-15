<?php

namespace App\Layers\Domain\Entity\Shop\Order;

use App\Exceptions\DomainException;
use App\Layers\Domain\ValueObject\OrderStatus;
use App\Layers\Presentation\Requests\Shop\Order\UpdateRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderOption;

class OrderFactory
{
    /**
     * @param $model
     * @return OrderEntity
     * @throws DomainException
     */
    public function makeByModel($model): OrderEntity
    {
        return OrderEntity::make(
            id: $model->id,
            store_id: $model->customer->seat->store_id,
            seat_number: $model->customer->seat->number,
            status: OrderStatus::make(
                value: $model->status,
            ),
            name: $model->menuItem->name,
            options: collect($model->orderOptions)->map(function (OrderOption $item) {
                return OrderOptionEntity::make(
                    id: $item->id,
                    name: $item->menuItemOptionValue->MenuItemOption->name,
                    value: $item->menuItemOptionValue->value,
                );
            }),
            quantity: $model->quantity,
            price: $model->price,
            created_at: $model->created_at,
        );
    }

    /**
     * @param Order $model
     * @param UpdateRequest $request
     * @return OrderEntity
     * @throws DomainException
     */
    public function makeByModelAndRequest(
        Order $model,
        UpdateRequest $request
    ): OrderEntity {
        return OrderEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            seat_number: $model->seat_number,
            status: OrderStatus::make(
                value: $request->input('status'),
            ),
            name: $model->menuItem->name,
            options: collect($model->orderOptions)->map(function ($item) {
                /** @var OrderOption $item */
                return OrderOptionEntity::make(
                    id: $item->id,
                    name: $item->menuItemOptionValue->MenuItemOption->name,
                    value: $item->menuItemOptionValue->value,
                );
            }),
            quantity: (int)$model->quantity,
            price: (int)$model->price,
            created_at: $model->created_at,
        );
    }

    /**
     * @param $db_collection
     * @return OrderEntityList
     * @throws DomainException
     */
    public function makeListFromDbCollection($db_collection): OrderEntityList
    {
        return OrderEntityList::make(
            $db_collection->map(function ($item) {
                return $this->makeByModel($item);
            })
        );
    }
}
