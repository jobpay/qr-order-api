<?php

namespace App\Layers\Application\UseCase\Customer\Order;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Domain\Entity\Customer\Order\OrderFactory;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Layers\Presentation\Requests\Customer\Order\StoreRequest;
use App\Output\Output;

class StoreUseCase
{
    /**
     * @param CustomerFactory $customer_factory
     * @param CustomerRepository $customer_repository
     * @param OrderRepository $order_repository
     * @param OrderFactory $customer_order_factory
     */
    public function __construct(
        private readonly CustomerFactory    $customer_factory,
        private readonly CustomerRepository $customer_repository,
        private readonly OrderRepository $order_repository,
        private readonly OrderFactory  $customer_order_factory,
    ) {
    }

    /**
     * @param StoreRequest $request
     * @return Output
     */
    public function exec(
        StoreRequest $request,
    ): Output {
        $customer_model = $this->customer_repository->findWithSeatAndOrdersByToken($request->token);
        if (is_null($customer_model)) {
            return new Output(errors: ['座席の認証に失敗しました。']);
        }

        $customer_entity = $this->customer_factory->makeByModel($customer_model);
        if (!$customer_entity->getSeatStatus()->isSession() || !$customer_entity->getCustomerStatus()->isPresent()) {
            return new Output(errors: ['チェックインが完了していません。担当者にお問合せください。']);
        }

        try {
            $customer_order_entity_collection = $this->customer_order_factory->makeNewList($request);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $this->order_repository->store(
            customer_entity: $customer_entity,
            customer_order_entity_collection: $customer_order_entity_collection
        );

        return new Output();
    }
}
