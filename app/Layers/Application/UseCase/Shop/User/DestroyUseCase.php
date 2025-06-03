<?php

namespace App\Layers\Application\UseCase\Shop\User;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\User\UserFactory;
use App\Layers\Infrastructure\Repository\UserRepository;
use App\Output\Output;

class DestroyUseCase
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
     * @param int $user_id
     * @param int $store_id
     * @return Output
     */
    public function exec(
        int $user_id,
        int $store_id,
    ): Output {
        $user_model = $this->user_repository->find($user_id);
        if (is_null($user_model)) {
            return new Output(errors: ['指定されたユーザーが見つかりません。']);
        }

        try {
            $user_entity = $this->user_factory->makeByModel($user_model);

            // 他店舗のユーザーの場合はエラー
            if ($user_entity->isOtherStore($store_id)) {
                return new Output(errors: ['指定されたユーザーの削除権限がありません。']);
            }
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $this->user_repository->delete($user_entity);

        return new Output();
    }
}
