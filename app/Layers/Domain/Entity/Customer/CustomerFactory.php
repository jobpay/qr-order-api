<?php

namespace App\Layers\Domain\Entity\Customer;

use App\Exceptions\DomainException;
use App\Layers\Domain\ValueObject\CustomerStatus;
use App\Layers\Domain\ValueObject\SeatStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CustomerFactory
{
    /**
     * @param $model
     * @return CustomerEntity
     * @throws DomainException
     */
    public function makeByModel($model): CustomerEntity
    {
        return CustomerEntity::make(
            id: $model->id,
            store_id: $model->seat->store_id,
            store_name: $model->seat->store->name,
            seat_id: $model->seat_id,
            seat_number: $model->seat->number,
            token: $model->token,
            start_at: Carbon::parse($model->start_at),
            end_at: Carbon::parse($model->end_at),
            seat_status: SeatStatus::make(
                value: $model->seat->status,
            ),
            session_status: CustomerStatus::make(
                value: $model->status,
            ),
            sum_price: $model->sum_price ?? 0,
        );
    }

    /**
     * @param $db_collection
     * @return Collection
     * @throws DomainException
     */
    public function makeListFromDbCollection($db_collection): Collection
    {
        return $db_collection->map(function ($item) {
            return $this->makeByModel($item);
        });
    }
}
