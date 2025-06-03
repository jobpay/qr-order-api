<?php

namespace App\Layers\Application\UseCase\Shop\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Layers\Infrastructure\Service\MenuItemImageUploadService;
use App\Output\Output;

class DestroyUseCase
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
     * @param int $menu_item_id
     * @param int $store_id
     * @return Output
     * @throws \Exception
     */
    public function exec(
        int $menu_item_id,
        int $store_id,
    ): Output {

        $menu_item_model = $this->menu_item_repository->find($menu_item_id);
        if (is_null($menu_item_model)) {
            return new Output(errors: ['指定されたメニューが見つかりません。']);
        }

        try {
            $menu_item_entity = $this->menu_item_factory->makeByModel(
                model: $menu_item_model,
            );
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if($menu_item_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定されたメニューの削除権限がありません。']);
        }

        // storageから画像を削除
        $this->menu_item_image_upload_service->deleteFile(
            image: $menu_item_entity->getImage(),
        );

        try {
            $this->menu_item_repository->delete($menu_item_entity);
        } catch (\Exception $e) {
            return new Output(errors: [$e->getMessage()]);
        }

        return new Output();
    }
}
