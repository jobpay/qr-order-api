<?php

namespace App\Layers\Application\UseCase\Shop\Order;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Order\OrderFactory;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Layers\Presentation\Requests\Shop\Order\ListRequest;
use App\Output\Output;

class ListUseCase
{
    /**
     * @param OrderRepository $order_repository
     * @param OrderFactory $order_factory
     */
    public function __construct(
        private readonly OrderRepository $order_repository,
        private readonly OrderFactory $order_factory,
    ) {
    }

    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        ListRequest $request,
        int $store_id,
    ): Output {
        $order_db_collection = $this->order_repository->get($request, $store_id);
        $total = $this->order_repository->getTotal($request, $store_id);

        try {
            $order_entity_list = $this->order_factory->makeListFromDbCollection($order_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$order_entity_list, $total]);
    }
}
