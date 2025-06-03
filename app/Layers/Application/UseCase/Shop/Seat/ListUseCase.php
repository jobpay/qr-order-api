<?php

namespace App\Layers\Application\UseCase\Shop\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Seat\SeatFactory;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Layers\Presentation\Requests\Shop\Seat\ListRequest;
use App\Output\Output;

class ListUseCase
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
     * @param ListRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        ListRequest $request,
        int $store_id,
    ): Output {
        $seat_db_collection = $this->seat_repository->get($request, $store_id);
        $total = $this->seat_repository->getTotal($store_id);
        try {
            $seat_entity_list = $this->seat_factory->makeListFromDbCollection($seat_db_collection);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        return new Output(data: [$seat_entity_list, $total]);
    }
}
