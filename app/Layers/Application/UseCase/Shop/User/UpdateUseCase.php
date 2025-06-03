<?php

namespace App\Layers\Application\UseCase\Shop\User;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\User\UserFactory;
use App\Layers\Infrastructure\Repository\UserRepository;
use App\Layers\Presentation\Requests\Shop\User\UpdateRequest;
use App\Output\Output;

class UpdateUseCase
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
     * @param UpdateRequest $request
     * @return Output
     */
    public function exec(
        int $user_id,
        int $store_id,
        UpdateRequest $request,
    ): Output {
        $user_model = $this->user_repository->find($user_id);
        if (is_null($user_model)) {
            return new Output(errors: ['指定されたユーザーが見つかりません。']);
        }

        try {
            $user_entity = $this->user_factory->makeByModel($user_model);

            // 他店舗のユーザーの場合はエラー
            if ($user_entity->isOtherStore($store_id)) {
                return new Output(errors: ['指定されたユーザーの編集権限がありません。']);
            }

            // 対象のユーザーで、指定されたメールアドレスが既に登録されている場合はエラー
            $is_same_email = $this->user_repository->existsEmailAndOtherUser(
                email: $request->input('email'),
                user_entity: $user_entity,
            );
            if ($is_same_email) {
                return new Output(errors: ['指定されたメールアドレスは既に登録されています。']);
            }

            $update_user_entity = $this->user_factory->makeUpdate(
                entity: $user_entity,
                request: $request,
            );
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $this->user_repository->update($update_user_entity);

        return new Output();
    }
}
