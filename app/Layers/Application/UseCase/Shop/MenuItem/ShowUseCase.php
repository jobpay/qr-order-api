<?php

namespace App\Layers\Application\UseCase\Shop\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Output\Output;

class ShowUseCase
{
    /**
     * @param MenuItemRepository $menu_item_repository
     * @param MenuItemFactory $menu_item_factory
     */
    public function __construct(
        private readonly MenuItemRepository $menu_item_repository,
        private readonly MenuItemFactory $menu_item_factory,
    ) {
    }

    /**
     * @param int $menu_item_id
     * @param int $store_id
     * @return Output
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
            $menu_item_entity = $this->menu_item_factory->makeByModel($menu_item_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if ($menu_item_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定されたメニューの閲覧権限がありません。']);
        }

        return new Output(data: [$menu_item_entity]);
    }
}
