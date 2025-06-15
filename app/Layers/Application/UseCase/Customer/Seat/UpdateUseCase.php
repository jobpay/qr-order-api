<?php

namespace App\Layers\Application\UseCase\Customer\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Customer\CustomerFactory;
use App\Layers\Domain\Entity\Customer\Seat\SeatFactory;
use App\Layers\Infrastructure\Repository\CustomerRepository;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Layers\Presentation\Requests\Customer\Seat\UpdateRequest;
use App\Output\Output;

class UpdateUseCase
{
    /**
     * @param CustomerRepository $customer_repository
     * @param CustomerFactory $customer_factory
     * @param SeatRepository $seat_repository
     * @param SeatFactory $customer_seat_factory
     */
    public function __construct(
        private readonly CustomerRepository $customer_repository,
        private readonly CustomerFactory    $customer_factory,
        private readonly SeatRepository     $seat_repository,
        private readonly SeatFactory        $customer_seat_factory,
    ) {
    }

    /**
     * @param UpdateRequest $request
     * @return Output
     * @throws DomainException
     */
    public function exec(
        UpdateRequest $request,
    ): Output {
        $customer_model = $this->customer_repository->findWithSeatAndOrdersByToken($request->token);
        if (is_null($customer_model)) {
            return new Output(errors: ['座席の認証に失敗しました。']);
        }

        $customer_entity = $this->customer_factory->makeByModel($customer_model);
        if (!$customer_entity->getSeatStatus()->isSession() || !$customer_entity->getCustomerStatus()->isPresent()) {
            return new Output(errors: ['チェックインが完了していません。担当者にお問合せください。']);
        }

        $seat_model = $this->seat_repository->find($customer_entity->getSeatId());
        if (is_null($seat_model)) {
            return new Output(errors: ['指定された座席が見つかりません。']);
        }

        try {
            $customer_seat_entity = $this->customer_seat_factory->makeByModel($seat_model);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        if (!$customer_seat_entity->getStatus()->isPresent()) {
            return new Output(errors: ['指定された座席には在席中のセッションがありません。']);
        }

        $this->customer_repository->update($customer_seat_entity);

        return new Output(data: [$customer_entity]);
    }
}
