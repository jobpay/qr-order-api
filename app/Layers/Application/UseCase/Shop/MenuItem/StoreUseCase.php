<?php

namespace App\Layers\Application\UseCase\Shop\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Layers\Infrastructure\Service\MenuItemImageUploadService;
use App\Layers\Presentation\Requests\Shop\MenuItem\StoreRequest;
use App\Output\Output;

class StoreUseCase
{
    /**
     * @param MenuItemRepository $menu_item_repository
     * @param MenuItemFactory $menu_item_factory
     * @param MenuItemImageUploadService $menu_item_image_upload_service
     */
    public function __construct(
        private readonly MenuItemRepository $menu_item_repository,
        private readonly MenuItemFactory $menu_item_factory,
        private readonly MenuItemImageUploadService $menu_item_image_upload_service,
    ) {
    }

    /**
     * @param StoreRequest $request
     * @param int $store_id
     * @return Output
     * @throws \Exception
     */
    public function exec(
        StoreRequest $request,
        int $store_id,
    ): Output {
        try {
            $menu_item_entity = $this->menu_item_factory->makeNew($request, $store_id);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        // store_id と number が一致するメニューアイテムが既に存在する場合はエラー
        if ($this->menu_item_repository->existsByStoreIdAndNumber($store_id, $menu_item_entity->getNumber())) {
            return new Output(errors: ['メニュー番号が重複しています']);
        }

        // storageに画像を登録
        $image_url = $this->menu_item_image_upload_service->putFileAs(
            image: $menu_item_entity->getImage(),
        );

        try {
            $this->menu_item_repository->create($menu_item_entity, $image_url);
        } catch (\Exception $e) {
            return new Output(errors: [$e->getMessage()]);
        }

        return new Output();
    }
}
