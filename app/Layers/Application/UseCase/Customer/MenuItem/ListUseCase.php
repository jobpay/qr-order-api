<?php

namespace App\Layers\Application\UseCase\Customer\MenuItem;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemFactory;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\MenuItemRepository;
use App\Layers\Presentation\Requests\Customer\MenuItem\ListRequest;
use App\Output\Output;

class ListUseCase
{
    /**
     * @param CustomerFactory $customer_factory
     * @param CustomerRepository $customer_repository
     * @param MenuItemRepository $menu_item_repository
     * @param MenuItemFactory $menu_item_factory
     */
    public function __construct(
        private readonly CustomerFactory    $customer_factory,
        private readonly CustomerRepository $customer_repository,
        private readonly MenuItemRepository $menu_item_repository,
        private readonly MenuItemFactory    $menu_item_factory,
    ) {
    }

    /**
     * @param ListRequest $request
     * @return Output
     * @throws DomainException
     */
    public function exec(
        ListRequest $request,
    ): Output {
        $customer_model = $this->customer_repository->findWithSeatAndOrdersByToken($request->token);
        if (is_null($customer_model)) {
            return new Output(errors: ['座席の認証に失敗しました。']);
        }

        $customer_entity = $this->customer_factory->makeByModel($customer_model);
        if (!$customer_entity->getSeatStatus()->isSession() || !$customer_entity->getCustomerStatus()->isPresent()) {
            return new Output(errors: ['チェックインが完了していません。担当者にお問合せください。']);
        }

        $menu_item_db_collection = $this->menu_item_repository->getForCustomer($customer_entity, $request);
        try {
            $menu_item_list = $this->menu_item_factory->makeListFromDbCollection($menu_item_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$menu_item_list]);
    }
}
