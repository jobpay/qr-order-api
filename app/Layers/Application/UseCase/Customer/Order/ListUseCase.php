<?php

namespace App\Layers\Application\UseCase\Customer\Order;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Domain\Entity\Customer\Order\OrderFactory;
use App\Layers\Domain\ValueObject\Invoice;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Layers\Presentation\Requests\Customer\Order\ListRequest;
use App\Output\Output;

class ListUseCase
{
    /**
     * @param CustomerFactory $customer_factory
     * @param OrderFactory $customer_order_factory
     * @param CustomerRepository $customer_repository
     * @param OrderRepository $order_repository
     */
    public function __construct(
        private readonly CustomerFactory    $customer_factory,
        private readonly OrderFactory  $customer_order_factory,
        private readonly CustomerRepository $customer_repository,
        private readonly OrderRepository $order_repository,
    ) {
    }

    /**
     * @param ListRequest $request
     * @return Output
     * @throws DomainException
     */
    public function exec(
        ListRequest $request,
    ): Output {
        $customer_model = $this->customer_repository->findWithSeatAndOrdersByToken($request->token);
        if (is_null($customer_model)) {
            return new Output(errors: ['座席の認証に失敗しました。']);
        }

        $customer_entity = $this->customer_factory->makeByModel($customer_model);
        if (!$customer_entity->getSeatStatus()->isSession() || !$customer_entity->getCustomerStatus()->isPresent()) {
            return new Output(errors: ['チェックインが完了していません。担当者にお問合せください。']);
        }

        $customer_order_db_collection = $this->order_repository->getForCustomer($customer_entity, $request);

        try {
            $customer_order_list = $this->customer_order_factory->makeListFromDbCollection($customer_order_db_collection);
            $invoice = Invoice::make($this->customer_order_factory->makeSumPriceFromEntityList($customer_order_list));
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$customer_order_list, $invoice]);
    }
}
