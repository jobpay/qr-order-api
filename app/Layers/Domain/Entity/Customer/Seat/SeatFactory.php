<?php

namespace App\Layers\Domain\Entity\Customer\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\ShopFactory;
use App\Layers\Domain\ValueObject\CustomerStatus;
use App\Layers\Domain\ValueObject\CustomerToken;
use App\Models\Seat;

class SeatFactory
{
    /**
     * @param ShopFactory $store_factory
     */
    public function __construct(
        private readonly ShopFactory $store_factory,
    ) {
    }

    /**
     * @param Seat $seat_model
     * @return SeatEntity
     * @throws DomainException
     */
    public function makeNew(Seat $seat_model): SeatEntity
    {
        return SeatEntity::make(
            id: null,
            seat_id: $seat_model->id,
            seat_number: $seat_model->number,
            store: $this->store_factory->makeByModel($seat_model->store),
            status: CustomerStatus::make(CustomerStatus::PRESENT),
            token: CustomerToken::make(seat_id: $seat_model->id),
        );
    }

    /**
     * @param Seat $seat_model
     * @return SeatEntity
     * @throws DomainException
     */
    public function makeByModel(Seat $seat_model): SeatEntity
    {
        // 最新のセッションを取得
        $customer_model = $seat_model->customers()->latest()->first();

        return SeatEntity::make(
            id: $customer_model?->id,
            seat_id: $seat_model->id,
            seat_number: $seat_model->number,
            store: $this->store_factory->makeByModel($seat_model->store),
            status: CustomerStatus::make($customer_model?->status),
            token: is_null($customer_model) ? null : CustomerToken::make($customer_model->token),
        );
    }
}
