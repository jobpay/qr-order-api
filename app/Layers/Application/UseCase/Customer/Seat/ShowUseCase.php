<?php

namespace App\Layers\Application\UseCase\Customer\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\Seat\SeatFactory;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Output\Output;

class ShowUseCase
{
    /**
     * @param SeatRepository $seat_repository
     * @param SeatFactory $customer_seat_factory
     */
    public function __construct(
        private readonly SeatRepository $seat_repository,
        private readonly SeatFactory    $customer_seat_factory,
    ) {
    }

    /**
     * @param int $seat_id
     * @return Output
     */
    public function exec(
        int $seat_id,
    ): Output {
        // 座席取得と同時に有効なセッションがあれば取得する
        $seat_model = $this->seat_repository->findWithStoreAndSession($seat_id);
        if (is_null($seat_model)) {
            return new Output(errors: ['指定された座席が見つかりません。']);
        }

        try {
            $customer_seat_entity = $this->customer_seat_factory->makeByModel($seat_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$customer_seat_entity]);
    }
}
