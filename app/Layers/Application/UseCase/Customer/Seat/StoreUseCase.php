<?php

namespace App\Layers\Application\UseCase\Customer\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\Seat\SeatFactory;
use App\Layers\Domain\ValueObject\CustomerStatus;
use App\Layers\Domain\ValueObject\SeatStatus;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Output\Output;

class StoreUseCase
{
    /**
     * @param SeatRepository $seat_repository
     * @param CustomerRepository $customer_repository
     * @param SeatFactory $customer_seat_factory
     */
    public function __construct(
        private readonly SeatRepository     $seat_repository,
        private readonly CustomerRepository $customer_repository,
        private readonly SeatFactory        $customer_seat_factory,
    ) {
    }

    /**
     * @param int $seat_id
     * @return Output
     * @throws \Exception
     */
    public function exec(
        int $seat_id,
    ): Output {
        $seat_model = $this->seat_repository->findWithStoreAndSession($seat_id);
        if (is_null($seat_model)) {
            return new Output(errors: ['指定された座席が見つかりません。']);
        }

        try {
            // 最新のセッションステータスを取得
            $current_customer_status = $seat_model->customers()?->latest()?->first()?->status;

            if ($current_customer_status === CustomerStatus::PRESENT) {
                $customer_entity = $this->customer_seat_factory->makeByModel($seat_model);

                return new Output(data: [$customer_entity]);
            }

            if ($seat_model->status !== SeatStatus::VACANT) {
                return new Output(errors: ['指定された座席は会計が完了していません。']);
            }

            $customer_entity = $this->customer_seat_factory->makeNew($seat_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $this->customer_repository->create($customer_entity);

        return new Output(data: [$customer_entity]);
    }
}
