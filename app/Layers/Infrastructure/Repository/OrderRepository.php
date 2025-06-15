<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Customer\CustomerEntity;
use App\Layers\Domain\Entity\Customer\Order\OrderEntity as CustomerOrderEntity;
use App\Layers\Domain\Entity\Customer\Order\OrderOptionEntity;
use App\Layers\Domain\Entity\Shop\Order\OrderEntity as ShopOrderEntity;
use App\Layers\Presentation\Requests\Customer\Order\ListRequest as CustomerListRequest;
use App\Layers\Presentation\Requests\Shop\Order\ListRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderOption;
use Illuminate\Database\Eloquent\Collection as DbCollection;
use Illuminate\Support\Collection;

class OrderRepository
{
    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return DbCollection
     */
    public function get(
        ListRequest $request,
        int $store_id,
    ): DbCollection {
        $query = Order::query()
            ->select(
                'orders.*',
                'seats.number as seat_number',
                'seats.store_id as store_id',
            )
            ->with(['orderOptions', 'menuItem'])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('seats', 'customers.seat_id', '=', 'seats.id')
            ->where('seats.store_id', $store_id)
            ->orderBy('orders.status')
            ->orderBy('orders.created_at', 'desc')
            ->limit($request->input('limit'))
            ->offset($request->input('offset'));

        return $query->get();
    }

    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return int
     */
    public function getTotal(
        ListRequest $request,
        int $store_id
    ): int {
        $query = Order::query()
            ->select(
                'orders.*',
                'seats.number as seat_number',
                'seats.store_id as store_id',
            )
            ->with(['orderOptions', 'menuItem'])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('seats', 'customers.seat_id', '=', 'seats.id')
            ->where('seats.store_id', $store_id);

        return $query->count();
    }

    /**
     * @param int $customer_id
     * @return DbCollection
     */
    public function getByCustomerId(int $customer_id): DbCollection
    {
        return Order::query()
            ->with([
                'customer',
                'orderOptions',
                'menuItem',
                'menuItem.menuItemOptions',
                'menuItem.menuItemOptions.menuItemOptionValues',
            ])
            ->where('customer_id', $customer_id)
            ->get();
    }

    /**
     * @param CustomerEntity $customer_entity
     * @param CustomerListRequest $request
     * @return DbCollection
     */
    public function getForCustomer(
        CustomerEntity $customer_entity,
        CustomerListRequest $request,
    ): DbCollection {
        return Order::query()
            ->with([
                'orderOptions',
                'menuItem',
                'menuItem.menuItemOptions',
                'menuItem.menuItemOptions.menuItemOptionValues',
            ])
            ->where('customer_id', $customer_entity->getId())
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->get();
    }

    /**
     * @param CustomerEntity $customer_entity
     * @param Collection $customer_order_entity_collection
     * @return void
     */
    public function store(
        CustomerEntity $customer_entity,
        Collection $customer_order_entity_collection
    ): void {
        // オーダーの登録
        $customer_order_entity_collection->each(function ($customer_order_entity) use ($customer_entity) {
            /** @var CustomerOrderEntity $customer_order_entity */
            $order = Order::query()->create([
                'customer_id' => $customer_entity->getId(),
                'menu_item_id' => $customer_order_entity->getMenuItemId(),
                'quantity' => $customer_order_entity->getQuantity(),
                'price' => $customer_order_entity->getTotalPrice(),
            ]);
            // オーダーオプションの登録
            $customer_order_entity->getOptions()->each(function ($option) use ($order) {
                /** @var OrderOptionEntity $option */
                OrderOption::create([
                    'order_id' => $order->id,
                    'menu_item_option_value_id' => $option->getOptionValueId(),
                ]);
            });
        });
    }

    /**
     * @param ShopOrderEntity $order_entity
     * @return void
     * @throws \Exception
     */
    public function update(ShopOrderEntity $order_entity): void
    {
        // オーダーの更新
        Order::query()->where('id', $order_entity->getId())->update([
            'status' => $order_entity->getStatus()->getValue(),
        ]);
    }

    /**
     * @param int $order_id
     * @return Order|null
     */
    public function find(int $order_id): ?Order
    {
        return Order::select(
            'orders.*',
            'seats.number as seat_number',
            'seats.store_id as store_id',
        )
            ->with(['orderOptions', 'menuItem'])
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('seats', 'customers.seat_id', '=', 'seats.id')
            ->where('orders.id', $order_id)
            ->first();
    }
}
