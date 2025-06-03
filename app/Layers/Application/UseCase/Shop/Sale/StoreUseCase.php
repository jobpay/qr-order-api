<?php

namespace App\Layers\Application\UseCase\Shop\Sale;

use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Domain\Entity\Shop\Sale\SaleFactory;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\OrderRepository;
use App\Layers\Infrastructure\Repository\SaleRepository;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Layers\Presentation\Requests\Shop\Sale\StoreRequest;
use App\Output\Output;

class StoreUseCase
{
    /**
     * @param CustomerRepository $customer_repository
     * @param SaleRepository $sale_repository
     * @param SeatRepository $seat_repository
     * @param OrderRepository $order_repository
     * @param CustomerFactory $customer_factory
     * @param SaleFactory $sale_factory
     */
    public function __construct(
        private readonly CustomerRepository $customer_repository,
        private readonly SaleRepository $sale_repository,
        private readonly SeatRepository $seat_repository,
        private readonly OrderRepository $order_repository,
        private readonly CustomerFactory $customer_factory,
        private readonly SaleFactory $sale_factory,
    ) {
    }

    /**
     * @param StoreRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        StoreRequest $request,
        int $store_id,
    ): Output {
        $customer_model = $this->customer_repository->find($request->input('customer_id'));
        $customer_entity = $this->customer_factory->makeByModel($customer_model);

        if (!$customer_entity->getCustomerStatus()->isClosed()) {
            return new Output(errors: ['在席中のため会計できません。']);
        }

        $order_collection = $this->order_repository->getByCustomerId($customer_entity->getId());
        $sale_entity_list = $this->sale_factory->makeListForCreate($order_collection);

        if ($sale_entity_list->containsOtherStore($store_id)) {
            return new Output(errors: ['他店舗の注文が含まれています']);
        }

        // 売上登録
        $this->sale_repository->bulkCreate($sale_entity_list);
        // 座席を空席に変更
        $this->seat_repository->updateVacant($customer_entity->getSeatId());

        return new Output();
    }
}
