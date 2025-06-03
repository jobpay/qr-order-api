<?php

namespace App\Layers\Application\UseCase\Shop\User;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\User\UserFactory;
use App\Layers\Infrastructure\Repository\UserRepository;
use App\Layers\Presentation\Requests\Shop\User\ListRequest;
use App\Output\Output;

class ListUseCase
{
    /**
     * @param UserRepository $user_repository
     * @param UserFactory $user_factory
     */
    public function __construct(
        private readonly UserRepository $user_repository,
        private readonly UserFactory $user_factory,
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
        $user_db_collection = $this->user_repository->get($request, $store_id);
        try {
            $user_list = $this->user_factory->makeListFromDbCollection($user_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$user_list]);
    }
}
