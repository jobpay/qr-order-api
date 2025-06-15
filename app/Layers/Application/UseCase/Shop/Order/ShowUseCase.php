<?php

namespace App\Layers\Application\UseCase\Shop\Order;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Order\OrderFactory;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Output\Output;

class ShowUseCase
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
     * @param int $order_id
     * @param int $store_id
     * @return Output
     */
    public function exec(
        int $order_id,
        int $store_id,
    ): Output {
        $order_model = $this->order_repository->find($order_id);
        if (is_null($order_model)) {
            return new Output(errors: ['指定された注文が見つかりません。']);
        }

        try {
            $order_entity = $this->order_factory->makeByModel($order_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if ($order_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定された注文の閲覧権限がありません。']);
        }

        return new Output(data: [$order_entity]);
    }
}
