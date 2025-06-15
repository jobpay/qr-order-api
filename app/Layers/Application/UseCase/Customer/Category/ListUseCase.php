<?php

namespace App\Layers\Application\UseCase\Customer\Category;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Category\CategoryFactory;
use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Infrastructure\Repository\CategoryRepository;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Presentation\Requests\Customer\Category\ListRequest;
use App\Output\Output;

class ListUseCase
{
    /**
     * @param CustomerFactory $customer_factory
     * @param CustomerRepository $customer_repository
     * @param CategoryRepository $category_repository
     * @param CategoryFactory $category_factory
     */
    public function __construct(
        private readonly CustomerFactory    $customer_factory,
        private readonly CustomerRepository $customer_repository,
        private readonly CategoryRepository $category_repository,
        private readonly CategoryFactory    $category_factory,
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

        $category_db_collection = $this->category_repository->getForCustomer($customer_entity, $request);
        $total = $this->category_repository->getTotal($customer_entity->getStoreId());

        try {
            $category_list = $this->category_factory->makeListFromDbCollection($category_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$category_list, $total]);
    }
}
