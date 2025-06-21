<?php

namespace App\Layers\Application\UseCase\Shop\Order;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Order\OrderFactory;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Layers\Presentation\Requests\Shop\Order\UpdateRequest;
use App\Output\Output;

class UpdateUseCase
{
    public function __construct(
        private readonly OrderRepository $order_repository,
        private readonly OrderFactory $order_factory,
    ) {
    }

    /**
     * @param int $order_id
     * @param int $store_id
     * @param UpdateRequest $request
     * @return Output
     * @throws \Exception
     */
    public function exec(
        int $order_id,
        int $store_id,
        UpdateRequest $request,
    ): Output {
        $order_model = $this->order_repository->find($order_id);
        if (is_null($order_model)) {
            return new Output(errors: ['指定されたオーダーが見つかりません。']);
        }

        try {
            $order_entity = $this->order_factory->makeByModelAndRequest(
                model: $order_model,
                request: $request,
            );
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if ($order_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定されたオーダーの編集権限がありません。']);
        }

        try {
            $this->order_repository->update($order_entity);
        } catch (\Exception $e) {
            return new Output(errors: [$e->getMessage()]);
        }

        return new Output();
    }
}
