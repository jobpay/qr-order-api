<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Shop\Sale\SaleEntity;
use App\Layers\Domain\Entity\Shop\Sale\SaleEntityList;
use App\Layers\Presentation\Requests\Shop\Sale\ListRequest;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SaleRepository
{
    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return Collection
     */
    public function get(
        ListRequest $request,
        int $store_id
    ): Collection {
        $sale = Sale::query()
            ->with([
                'store',
                'order',
                'order.customer',
                'order.customer.seat',
                'order.menuItem',
                'order.menuItem.category',
            ])
            ->where('store_id', $store_id)
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->orderBy('id', 'desc');

        if (!is_null($request->input('from'))) {
            $sale->where('paid_at', '>=', $request->input('from'));
        }

        if (!is_null($request->input('to'))) {
            $sale->where('paid_at', '<=', $request->input('to'));
        }

        if (!is_null($request->input('category_id'))) {
            $sale->whereHas('order.menuItem', function ($query) use ($request) {
                $query->where('category_id', $request->input('category_id'));
            });
        }

        return $sale->get();
    }

    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return int
     */
    public function sumAmount(
        ListRequest $request,
        int $store_id
    ): int {
        $sale = Sale::query()
            ->where('store_id', $store_id);

        if (!is_null($request->input('from'))) {
            $sale->where('paid_at', '>=', $request->input('from'));
        }

        if (!is_null($request->input('to'))) {
            $sale->where('paid_at', '<=', $request->input('to'));
        }

        if (!is_null($request->input('category_id'))) {
            $sale->whereHas('order.menuItem', function ($query) use ($request) {
                $query->where('category_id', $request->input('category_id'));
            });
        }

        return $sale->sum('amount');
    }

    /**
     * @param SaleEntityList $sale_entity_list
     * @return void
     */
    public function bulkCreate(SaleEntityList $sale_entity_list): void
    {
        $now = Carbon::now();
        Sale::query()->insert(
            $sale_entity_list->get()->map(function (SaleEntity $sale_entity) use ($now) {
                return [
                    'store_id' => $sale_entity->getStoreId(),
                    'order_id' => $sale_entity->getOrder()->getId(),
                    'amount' => $sale_entity->getAmount(),
                    'paid_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray()
        );
    }

    /**
     * @param int $sale_id
     * @return Sale|null
     */
    public function find(
        int $sale_id,
    ): ?Sale {
        return Sale::query()
            ->with([
                'store',
                'order',
                'order.customer',
                'order.customer.seat',
                'order.menuItem',
                'order.menuItem.category',
            ])
            ->where('id', $sale_id)
            ->first();
    }

    /**
     * @param SaleEntity $sale_entity
     * @return void
     */
    public function update(SaleEntity $sale_entity): void
    {
        Sale::query()->where('id', $sale_entity->getId())
            ->update([
                'amount' => $sale_entity->getAmount(),
            ]);
    }

    /**
     * @param SaleEntity $sale_entity
     * @return void
     */
    public function delete(SaleEntity $sale_entity): void
    {
        Sale::query()->where('id', $sale_entity->getId())->delete();
    }
}
