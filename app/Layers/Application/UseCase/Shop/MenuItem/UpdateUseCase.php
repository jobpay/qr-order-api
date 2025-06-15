<?php

namespace App\Layers\Application\UseCase\Shop\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Layers\Infrastructure\Service\MenuItemImageUploadService;
use App\Layers\Presentation\Requests\Shop\MenuItem\UpdateRequest;
use App\Output\Output;

class UpdateUseCase
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
     * @param UpdateRequest $request
     * @return Output
     * @throws \Exception
     */
    public function exec(
        int $menu_item_id,
        int $store_id,
        UpdateRequest $request,
    ): Output {

        $menu_item_model = $this->menu_item_repository->find($menu_item_id);
        if (is_null($menu_item_model)) {
            return new Output(errors: ['指定されたメニューが見つかりません。']);
        }

        try {
            $menu_item_entity = $this->menu_item_factory->makeByModelAndRequest(
                model: $menu_item_model,
                request: $request,
            );
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if ($menu_item_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定されたメニューの編集権限がありません。']);
        }

        // 作成済みの他メニューでstore_id と number が一致するものが存在する場合はエラー
        if ($this->menu_item_repository->existsByStoreIdAndCategoryIdAndOtherMenuId(
            $store_id,
            $menu_item_entity->getCategory()->getId(),
            $menu_item_entity->getNumber(),
            $menu_item_entity->getId(),
        )) {
            return new Output(errors: ['作成済みの他メニューと番号が重複しています']);
        }

        // storageに画像を登録
        $image_url = $this->menu_item_image_upload_service->putFileAs(
            image: $menu_item_entity->getImage(),
        );

        try {
            $this->menu_item_repository->update($menu_item_entity, $image_url);
        } catch (\Exception $e) {
            return new Output(errors: [$e->getMessage()]);
        }

        return new Output();
    }
}
