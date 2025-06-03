<?php

namespace App\Layers\Application\UseCase\Shop\Category;

use App\Layers\Domain\Entity\Category\CategoryFactory;
use App\Layers\Infrastructure\Repository\CategoryRepository;
use App\Layers\Presentation\Requests\Shop\Category\UpdateRequest;
use App\Output\Output;

class UpdateUseCase
{
    /**
     * @param CategoryRepository $category_repository
     * @param CategoryFactory $category_factory
     */
    public function __construct(
        private readonly CategoryRepository $category_repository,
        private readonly CategoryFactory    $category_factory,
    ) {
    }

    /**
     * @param UpdateRequest $request
     * @param int $category_id
     * @param int $store_id
     * @return Output
     */
    public function exec(
        UpdateRequest $request,
        int           $category_id,
        int           $store_id,
    ): Output {
        $category_model = $this->category_repository->find($category_id);
        if (is_null($category_model)) {
            return new Output(errors: ['指定されたカテゴリーが見つかりません。']);
        }

        $category_entity = $this->category_factory->makeByModel($category_model);

        if ($category_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定されたカテゴリーの更新権限がありません。']);
        }

        if ($this->category_repository->existsOrder($category_entity, $request->order)) {
            return new Output(errors: ['指定された表示順は既に使用されています。']);
        }

        $update_category_entity = $this->category_factory->makeUpdate($category_entity, $request);

        $this->category_repository->update($update_category_entity);

        return new Output();
    }
}
