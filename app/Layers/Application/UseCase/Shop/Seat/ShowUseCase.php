<?php

namespace App\Layers\Application\UseCase\Shop\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Domain\Entity\Shop\Order\OrderFactory;
use App\Layers\Domain\Entity\Shop\Seat\SeatFactory;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Output\Output;

class ShowUseCase
{
    /**
     * @param SeatRepository $seat_repository
     * @param CustomerRepository $customer_repository
     * @param OrderRepository $order_repository
     * @param SeatFactory $seat_factory
     * @param CustomerFactory $customer_factory
     * @param OrderFactory $order_factory
     */
    public function __construct(
        private readonly SeatRepository $seat_repository,
        private readonly CustomerRepository $customer_repository,
        private readonly OrderRepository $order_repository,
        private readonly SeatFactory $seat_factory,
        private readonly CustomerFactory $customer_factory,
        private readonly OrderFactory $order_factory,
    ) {
    }

    /**
     * @param int $seat_id
     * @param int $store_id
     * @return Output
     * @throws DomainException
     */
    public function exec(
        int $seat_id,
        int $store_id,
    ): Output {
        $seat_model = $this->seat_repository->find($seat_id);
        if (is_null($seat_model)) {
            return new Output(errors: ['指定された座席が見つかりません。']);
        }

        try {
            $seat_entity = $this->seat_factory->makeByModel($seat_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if($seat_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定された座席の閲覧権限がありません。']);
        }

        //　最新の客の注文履歴を取得
        if ($seat_entity->getStatus()->isSession()) {
            $customer_model = $this->customer_repository->findLatestBySeatId($seat_entity->getId());
            if (!is_null($customer_model)) {
                $customer_entity = $this->customer_factory->makeByModel($customer_model);
                $order_collection = $this->order_repository->getByCustomerId($customer_entity->getId());
                $order_entity_list = $this->order_factory->makeListFromDbCollection(
                    $order_collection
                );
            }
        }

        return new Output(data: [$seat_entity, $order_entity_list ?? null]);
    }
}
