<?php

namespace App\Layers\Application\UseCase\Shop\Category;

use App\Layers\Domain\Entity\Category\CategoryFactory;
use App\Layers\Infrastructure\Repository\CategoryRepository;
use App\Layers\Presentation\Requests\Shop\Category\StoreRequest;
use App\Output\Output;

class StoreUseCase
{
    /**
     * @param CategoryRepository $category_repository
     * @param CategoryFactory $category_factory
     */
    public function __construct(
        private readonly CategoryRepository $category_repository,
        private readonly CategoryFactory $category_factory,
    ) {
    }

    /**
     * @param StoreRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        StoreRequest $request,
        int $store_id,
    ): Output {
        $category_entity = $this->category_factory->makeNew($request, $store_id);

        if ($this->category_repository->existsOrder($category_entity, $request->order)) {
            return new Output(errors: ['指定された表示順は既に使用されています。']);
        }

        $this->category_repository->create($category_entity);

        return new Output();
    }
}
