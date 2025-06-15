<?php

namespace App\Layers\Domain\Entity\Customer\Order;

use App\Exceptions\DomainException;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Layers\Presentation\Requests\Customer\Order\ConfirmRequest;
use App\Layers\Presentation\Requests\Customer\Order\StoreRequest;
use App\Models\Order\Order;
use Illuminate\Support\Collection;

class OrderFactory
{
    public function __construct(
        private readonly MenuItemRepository $menu_item_repository,
    ) {
    }

    /**
     * @param StoreRequest $request
     * @return Collection
     */
    public function makeNewList(
        StoreRequest $request
    ): Collection {

        return collect($request->input('orders'))->map(function ($order) {
            // メニューの存在チェック
            $menu_item_model = $this->menu_item_repository->find($order['menu_item_id']);
            if (is_null($menu_item_model)) {
                throw new DomainException(['指定されたメニュー項目IDが見つかりません。']);
            }

            // option_value_idの存在チェック
            $customer_order_option_entity_collection = collect($order['option_value_ids'])->map(function ($option_value_id) {
                $option_value_model = $this->menu_item_repository->findOptionValue($option_value_id);
                if (is_null($option_value_model)) {
                    throw new DomainException(['指定されたオプション値IDが見つかりません。']);
                }

                return OrderOptionEntity::make(
                    id: null,
                    option_id: $option_value_model->menuItemOption->id,
                    option_name: $option_value_model->menuItemOption->name,
                    option_value_id: $option_value_id,
                    option_value_name: $option_value_model->value,
                    cost: (int)$option_value_model->cost,
                );
            });

            return OrderEntity::make(
                id: null,
                menu_item_id: $menu_item_model->id,
                menu_name: $menu_item_model->name,
                quantity: (int)$order['quantity'],
                options: $customer_order_option_entity_collection,
                price: (int)$menu_item_model->price,
            );
        });
    }

    /**
     * @param ConfirmRequest $request
     * @return Collection
     */
    public function makeConfirmList(
        ConfirmRequest $request
    ): Collection {

        return collect($request->input('orders'))->map(function ($order) {
            // メニューの存在チェック
            $menu_item_model = $this->menu_item_repository->find($order['menu_item_id']);
            if (is_null($menu_item_model)) {
                throw new DomainException(['指定されたメニュー項目IDが見つかりません。']);
            }

            // option_value_idの存在チェック
            $customer_order_option_entity_collection = collect($order['option_value_ids'])->map(function ($option_value_id) {
                $option_value_model = $this->menu_item_repository->findOptionValue($option_value_id);
                if (is_null($option_value_model)) {
                    throw new DomainException(['指定されたオプション値IDが見つかりません。']);
                }

                return OrderOptionEntity::make(
                    id: null,
                    option_id: $option_value_model->menuItemOption->id,
                    option_name: $option_value_model->menuItemOption->name,
                    option_value_id: $option_value_id,
                    option_value_name: $option_value_model->value,
                    cost: (int)$option_value_model->cost,
                );
            });

            return OrderEntity::make(
                id: null,
                menu_item_id: $menu_item_model->id,
                menu_name: $menu_item_model->name,
                quantity: (int)$order['quantity'],
                options: $customer_order_option_entity_collection,
                price: (int)$menu_item_model->price,
            );
        });
    }

    /**
     * @param Collection $customer_order_db_collection
     * @return Collection
     */
    public function makeListFromDbCollection(
        Collection $customer_order_db_collection
    ): Collection {
        return $customer_order_db_collection->map(function ($customer_order_model) {
            /** @var Order $customer_order_model */
            return OrderEntity::make(
                id: $customer_order_model->id,
                menu_item_id: $customer_order_model->menu_item_id,
                menu_name: $customer_order_model->menuItem->name,
                quantity: $customer_order_model->quantity,
                options: $customer_order_model->orderOptions->map(function ($order_option) {
                    return OrderOptionEntity::make(
                        id: $order_option->id,
                        option_id: $order_option->menuItemOptionValue->menuItemOption->id,
                        option_name: $order_option->menuItemOptionValue->menuItemOption->name,
                        option_value_id: $order_option->menuItemOptionValue->id,
                        option_value_name: $order_option->menuItemOptionValue->value,
                        cost: (int)$order_option->menuItemOptionValue->cost,
                    );
                }),
                price: (int)$customer_order_model->price,
            );
        });
    }

    /**
     * @param Collection $customer_order_entity_list
     * @return int
     */
    public function makeSumPriceFromEntityList(
        Collection $customer_order_entity_list
    ): int {
        return $customer_order_entity_list->sum(function ($customer_order_entity) {
            /** @var OrderEntity $customer_order_entity */
            return $customer_order_entity->getTotalPrice();
        });
    }
}
