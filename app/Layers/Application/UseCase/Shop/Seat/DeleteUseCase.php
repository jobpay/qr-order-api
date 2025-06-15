<?php

namespace App\Layers\Application\UseCase\Shop\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Seat\SeatFactory;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Output\Output;

class DeleteUseCase
{
    /**
     * @param SeatRepository $seat_repository
     * @param SeatFactory $seat_factory
     */
    public function __construct(
        private readonly SeatRepository $seat_repository,
        private readonly SeatFactory $seat_factory,
    ) {
    }

    /**
     * @param int $seat_id
     * @param int $store_id
     * @return Output
     */
    public function exec(
        int $seat_id,
        int $store_id,
    ): Output {
        $seat_model = $this->seat_repository->find($seat_id);
        if (is_null($seat_model)) {
            return new Output(errors: ['指定された座席が見つかりません。']);
        }

        try {
            $seat_entity = $this->seat_factory->makeByModel($seat_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if ($seat_entity->isOtherStore($store_id)) {
            return new Output(errors: ['指定された座席の削除権限がありません。']);
        }

        $this->seat_repository->delete($seat_entity);

        return new Output();
    }
}
