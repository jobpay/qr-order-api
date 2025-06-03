<?php

namespace App\Layers\Application\UseCase\Shop\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Layers\Presentation\Requests\Shop\MenuItem\ListRequest;
use App\Output\Output;

class ListUseCase
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
     * @param ListRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        ListRequest $request,
        int $store_id,
    ): Output {
        $menu_item_db_collection = $this->menu_item_repository->get($request, $store_id);
        $total = $this->menu_item_repository->getTotal($request, $store_id);

        try {
            $menu_item_list = $this->menu_item_factory->makeListFromDbCollection($menu_item_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$menu_item_list, $total]);
    }
}
