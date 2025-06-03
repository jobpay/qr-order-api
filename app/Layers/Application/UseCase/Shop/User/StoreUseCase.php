<?php

namespace App\Layers\Application\UseCase\Shop\User;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\User\UserFactory;
use App\Layers\Infrastructure\Repository\UserRepository;
use App\Layers\Presentation\Requests\Shop\User\StoreRequest;
use App\Output\Output;

class StoreUseCase
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
     * @param StoreRequest $request
     * @param int $store_id
     * @return Output
     * @throws \Exception
     */
    public function exec(
        StoreRequest $request,
        int $store_id,
    ): Output {
        try {
            $user_entity = $this->user_factory->makeNew($request, $store_id);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        // 指定されたメールアドレスが既に登録されている場合はエラー
        $is_same_email = $this->user_repository->existsEmail(
            email: $request->input('email'),
            user_entity: $user_entity,
        );
        if ($is_same_email) {
            return new Output(errors: ['指定されたメールアドレスは既に登録されています。']);
        }

        $this->user_repository->create($user_entity);

        return new Output();
    }
}
