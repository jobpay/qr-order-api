<?php

namespace App\Layers\Application\UseCase\Shop\Shop;

use App\Layers\Domain\Entity\Shop\ShopFactory;
use App\Layers\Infrastructure\Repository\StoreRepository;
use App\Layers\Infrastructure\Service\StoreImageUploadService;
use App\Layers\Presentation\Requests\Shop\Shop\UpdateRequest;
use App\Output\Output;
use Exception;

class UpdateUseCase
{
    /**
     * @param StoreRepository $store_repository
     * @param ShopFactory $store_factory
     * @param StoreImageUploadService $store_image_upload_service
     */
    public function __construct(
        private readonly StoreRepository         $store_repository,
        private readonly ShopFactory             $store_factory,
        private readonly StoreImageUploadService $store_image_upload_service,
    ) {
    }

    /**
     * @param int $store_id
     * @param UpdateRequest $request
     * @return Output
     * @throws \App\Exceptions\DomainException
     * @throws Exception
     */
    public function exec(
        int $store_id,
        UpdateRequest $request,
    ): Output {
        $store_model = $this->store_repository->find($store_id);
        if (is_null($store_model)) {
            return new Output(errors: ['店舗が見つかりません。']);
        }

        $store_entity = $this->store_factory->makeByModel($store_model);

        $update_store_entity = $this->store_factory->makeUpdate($store_entity, $request);

        // storageに画像を登録
        $image_url = $this->store_image_upload_service->putFileAs(
            image: $update_store_entity->getLogo(),
        );

        $this->store_repository->update($update_store_entity, $image_url);

        return new Output();
    }
}
