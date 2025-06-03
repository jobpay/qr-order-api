<?php

namespace App\Layers\Application\UseCase\Shop\Shop;

use App\Layers\Domain\Entity\Shop\ShopFactory;
use App\Layers\Domain\Entity\Shop\User\UserFactory;
use App\Layers\Infrastructure\Repository\StoreRepository;
use App\Layers\Infrastructure\Service\StoreImageUploadService;
use App\Layers\Presentation\Requests\Shop\Shop\StoreRequest;
use App\Output\Output;
use Exception;
use Illuminate\Support\Facades\Auth;

class StoreUseCase
{
    /**
     * @param StoreRepository $store_repository
     * @param ShopFactory $store_factory
     * @param UserFactory $user_factory
     * @param StoreImageUploadService $store_image_upload_service
     */
    public function __construct(
        private readonly StoreRepository         $store_repository,
        private readonly ShopFactory             $store_factory,
        private readonly UserFactory             $user_factory,
        private readonly StoreImageUploadService $store_image_upload_service,
    ) {
    }

    /**
     * @param StoreRequest $request
     * @return Output
     * @throws Exception
     */
    public function exec(
        StoreRequest $request,
    ): Output {
        $store_entity = $this->store_factory->makeNew($request);
        $user_entity = $this->user_factory->makeNewFirst($request);

        // storageに画像を登録
        $image_url = $this->store_image_upload_service->putFileAs(
            image: $store_entity->getLogo(),
        );

        $user = $this->store_repository->createWithUser($store_entity, $user_entity, $image_url);
        Auth::login($user);

        return new Output();
    }
}
