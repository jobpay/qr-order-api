<?php

namespace App\Layers\Application\UseCase\Shop\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\Entity\Shop\Seat\SeatFactory;
use App\Layers\Infrastructure\Repository\SeatRepository;
use App\Layers\Presentation\Requests\Shop\Seat\StoreRequest;
use App\Output\Output;

class StoreUseCase
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
     * @param StoreRequest $request
     * @param int $store_id
     * @return Output
     */
    public function exec(
        StoreRequest $request,
        int $store_id,
    ): Output {
        try {
            $seat_entity = $this->seat_factory->makeNew($request, $store_id);
        } catch (DomainException $e) {
            return new Output(errors: $e->getMessages());
        }

        $seat_model = $this->seat_repository->create($seat_entity);

        $seat_entity_without_qr_code = $this->seat_factory->makeByModel($seat_model);

        $this->seat_repository->updateQrCode($seat_entity_without_qr_code);

        return new Output();
    }
}
