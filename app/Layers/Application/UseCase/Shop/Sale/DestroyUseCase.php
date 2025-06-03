<?php

namespace App\Layers\Application\UseCase\Shop\Sale;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Sale\SaleFactory;
use App\Layers\Infrastructure\Repository\SaleRepository;
use App\Output\Output;

class DestroyUseCase
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
     * @param int $store_id
     * @return Output
     */
    public function exec(
        int $sale_id,
        int $store_id,
    ): Output {
        $sale_model = $this->sale_repository->find($sale_id);
        try {
            $sale_entity = $this->sale_factory->makeByModel($sale_model);

            if ($sale_entity->isOtherStore($store_id)) {
                return new Output(errors: ['他店舗のデータは削除できません。']);
            }
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $this->sale_repository->delete($sale_entity);

        return new Output();
    }
}
