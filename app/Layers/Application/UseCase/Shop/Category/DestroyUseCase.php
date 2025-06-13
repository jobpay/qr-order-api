<?php

namespace App\Layers\Application\UseCase\Shop\Category;

use App\Layers\Domain\Entity\Category\CategoryFactory;
use App\Layers\Infrastructure\Repository\CategoryRepository;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Output\Output;

class DestroyUseCase
{
    /**
     * @param CategoryRepository $category_repository
     * @param MenuItemRepository $menu_item_repository
     * @param CategoryFactory $category_factory
     */
    public function __construct(
        private readonly CategoryRepository $category_repository,
        private readonly MenuItemRepository $menu_item_repository,
        private readonly CategoryFactory $category_factory,
    ) {
    }

    /**
     * @param int $category_id
     * @param int $store_id
     * @return Output
     */
    public function exec(
        int $category_id,
        int $store_id,
    ): Output {
        $category_model = $this->category_repository->find($category_id);
        if (is_null($category_model)) {
            return new Output(errors: ['指定されたカテゴリーが見つかりません。']);
        }

        if ($this->menu_item_repository->existsByCategoryId($category_id)) {
            return new Output(errors: ['指定されたカテゴリーに紐づく商品が存在します。']);
        }

        $category_entity = $this->category_factory->makeByModel($category_model);

        if($category_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定されたカテゴリーの削除権限がありません。']);
        }

        $this->category_repository->delete($category_entity);

        return new Output();
    }
}
