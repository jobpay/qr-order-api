<?php

namespace App\Layers\Application\UseCase\Shop\Shop;

use App\Layers\Domain\Entity\Shop\ShopFactory;
use App\Layers\Infrastructure\Repository\StoreRepository;
use App\Output\Output;

class ShowUseCase
{
    /**
     * @param StoreRepository $store_repository
     * @param ShopFactory $store_factory
     */
    public function __construct(
        private readonly StoreRepository $store_repository,
        private readonly ShopFactory     $store_factory,
    ) {
    }

    /**
     * @param int|null $store_id
     * @return Output
     */
    public function exec(
        ?int $store_id,
    ): Output {
        $store_model = $this->store_repository->find($store_id);
        if (is_null($store_model)) {
            return new Output(errors: ['店舗が見つかりません。']);
        }

        $store_entity = $this->store_factory->makeByModel($store_model);

        return new Output(data: [$store_entity]);
    }
}
