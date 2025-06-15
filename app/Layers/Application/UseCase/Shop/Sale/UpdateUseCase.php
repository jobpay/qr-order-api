<?php

namespace App\Layers\Application\UseCase\Shop\Sale;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Sale\SaleFactory;
use App\Layers\Infrastructure\Repository\SaleRepository;
use App\Layers\Presentation\Requests\Shop\Sale\UpdateRequest;
use App\Output\Output;

class UpdateUseCase
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
     * @param int $sale_id
     * @param UpdateRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        int $sale_id,
        UpdateRequest $request,
        int $store_id,
    ): Output {
        $sale_model = $this->sale_repository->find($sale_id);

        try {
            $sale_entity = $this->sale_factory->makeByModel($sale_model);

            if ($sale_entity->isOtherStore($store_id)) {
                return new Output(errors: ['他店舗のデータは更新できません。']);
            }

            $update_sale_entity = $this->sale_factory->makeUpdate($sale_entity, $request);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $this->sale_repository->update($update_sale_entity);

        return new Output();
    }
}
