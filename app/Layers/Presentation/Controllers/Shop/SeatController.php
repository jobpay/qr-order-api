<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\Seat\DeleteUseCase;
use App\Layers\Application\UseCase\Shop\Seat\ListUseCase;
use App\Layers\Application\UseCase\Shop\Seat\ShowUseCase;
use App\Layers\Application\UseCase\Shop\Seat\StoreUseCase;
use App\Layers\Application\UseCase\Shop\Seat\UpdateUseCase;
use App\Layers\Domain\Entity\Shop\Order\OrderEntity;
use App\Layers\Domain\Entity\Shop\Order\OrderEntityList;
use App\Layers\Domain\Entity\Shop\Order\OrderOptionEntity;
use App\Layers\Domain\Entity\Shop\Seat\SeatEntity;
use App\Layers\Presentation\Requests\Shop\Seat\ListRequest;
use App\Layers\Presentation\Requests\Shop\Seat\StoreRequest;
use App\Layers\Presentation\Requests\Shop\Seat\UpdateRequest;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    /**
     * @param ListRequest $request
     * @param ListUseCase $use_case
     * @return JsonResponse
     */
    public function index(
        ListRequest $request,
        ListUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json([
            'seats' => $output->getData()[0]->map(function ($item) {
                /** @var SeatEntity $item */
                return [
                    'id' => $item->getId(),
                    'number' => $item->getNumber(),
                    'order' => $item->getOrder(),
                    'start_at' => $item->getStartAt()?->format('H:i'),
                    'end_at' => $item->getEndAt()?->format('H:i'),
                    'status' => $item->getStatus()->getName(),
                ];
            }),
            'total' => $output->getData()[1],
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param StoreUseCase $use_case
     * @return JsonResponse
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function store(
        StoreRequest $request,
        StoreUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $seat_id
     * @param ShowUseCase $use_case
     * @return JsonResponse
     * @throws \App\Exceptions\DomainException
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function show(
        int $seat_id,
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($seat_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var SeatEntity $seat_entity */
        $seat_entity = $output->getData()[0];
        /** @var OrderEntityList|null $order_entity_list */
        $order_entity_list = $output->getData()[1];

        return response()->json([
            'id' => $seat_entity->getId(),
            'number' => $seat_entity->getNumber(),
            'order' => $seat_entity->getOrder(),
            'qr_code' => $seat_entity->getQrCode(),
            'start_at' => $seat_entity->getStartAt()?->format('Y/m/d H:i'),
            'end_at' => $seat_entity->getEndAt()?->format('Y/m/d H:i'),
            'status' => $seat_entity->getStatus()->getName(),
            'orders' => is_null($order_entity_list) ? null : $order_entity_list->get()
                ->map(function (OrderEntity $item) {
                    return [
                        'id' => $item->getId(),
                        'name' => $item->getName(),
                        'count' => $item->getQuantity(),
                        'created_at' => $item->getCreatedAt()?->format('m/d H:i'),
                        'status' => $item->getStatus()->getName(),
                        'options' => $item->getOptions()->map(function (OrderOptionEntity $option) {
                            return [
                                'id' => $option->getId(),
                                'name' => $option->getName(),
                                'value' => $option->getValue(),
                            ];
                        }),
                    ];
                }),
        ]);
    }

    /**
     * @param int $seat_id
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     */
    public function update(
        int $seat_id,
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request, $seat_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $seat_id
     * @param DeleteUseCase $use_case
     * @return JsonResponse
     */
    public function destroy(
        int $seat_id,
        DeleteUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($seat_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
