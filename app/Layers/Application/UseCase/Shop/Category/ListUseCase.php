<?php

namespace App\Layers\Application\UseCase\Shop\Category;

use App\Layers\Domain\Entity\Category\CategoryFactory;
use App\Layers\Infrastructure\Repository\CategoryRepository;
use App\Layers\Presentation\Requests\Shop\Category\ListRequest;
use App\Output\Output;

class ListUseCase
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
     * @param ListRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        ListRequest $request,
        int $store_id,
    ): Output {
        $category_db_collection = $this->category_repository->get($request, $store_id);
        $total = $this->category_repository->getTotal($store_id);

        $category_entity_list = $this->category_factory->makeListFromDbCollection($category_db_collection);

        return new Output(data: [$category_entity_list, $total]);
    }
}
