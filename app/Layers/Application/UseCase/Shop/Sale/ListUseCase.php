<?php

namespace App\Layers\Application\UseCase\Shop\Sale;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Sale\SaleFactory;
use App\Layers\Infrastructure\Repository\SaleRepository;
use App\Layers\Presentation\Requests\Shop\Sale\ListRequest;
use App\Output\Output;

class ListUseCase
{
    /**
     * @param SaleRepository $sale_repository
     * @param SaleFactory $sale_factory
     */
    public function __construct(
        private readonly SaleRepository $sale_repository,
        private readonly SaleFactory $sale_factory,
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
        $sale_db_collection = $this->sale_repository->get($request, $store_id);
        $sum_amount = $this->sale_repository->sumAmount($request, $store_id);

        try {
            $sale_list = $this->sale_factory->makeListFromDbCollection($sale_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$sale_list, $sum_amount]);
    }
}
