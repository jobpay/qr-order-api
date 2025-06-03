<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\Order\ListUseCase;
use App\Layers\Application\UseCase\Shop\Order\ShowUseCase;
use App\Layers\Application\UseCase\Shop\Order\UpdateUseCase;
use App\Layers\Domain\Entity\Shop\Order\OrderEntity;
use App\Layers\Domain\Entity\Shop\Order\OrderEntityList;
use App\Layers\Domain\Entity\Shop\Order\OrderOptionEntity;
use App\Layers\Presentation\Requests\Shop\Order\ListRequest;
use App\Layers\Presentation\Requests\Shop\Order\UpdateRequest;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
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

        /** @var OrderEntityList $order_entity_list */
        $order_entity_list = $output->getData()[0];

        return response()->json([
            'orders' => $order_entity_list->get()->map(function ($item) {
                /** @var OrderEntity $item */
                return [
                    'id' => $item->getId(),
                    'seat_number' => $item->getSeatNumber(),
                    'status' => $item->getStatus()->getName(),
                    'name' => $item->getName(),
                    'option' => $item->getOptions()->map(function ($item) {
                        /** @var OrderOptionEntity $item */
                        return [
                            'id' => $item->getId(),
                            'name' => $item->getName(),
                            'value' => $item->getValue(),
                        ];
                    }),
                    'quantity' => $item->getQuantity(),
                    'created_at' => $item->getCreatedAt()?->format('Y-m-d H:i:s'),
                ];
            }),
            'total' => $output->getData()[1],
        ]);
    }

    /**
     * @param int $order_id
     * @param ShowUseCase $use_case
     * @return JsonResponse
     */
    public function show(
        int $order_id,
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($order_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var OrderEntity $order_entity */
        $order_entity = $output->getData()[0];

        return response()->json([
            'id' => $order_entity->getId(),
            'seat_number' => $order_entity->getSeatNumber(),
            'status' => $order_entity->getStatus()->getName(),
            'name' => $order_entity->getName(),
            'option' => $order_entity->getOptions()->map(function ($item) {
                /** @var OrderOptionEntity $item */
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'value' => $item->getValue(),
                ];
            }),
            'quantity' => $order_entity->getQuantity(),
            'created_at' => $order_entity->getCreatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param int $order_id
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        int $order_id,
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($order_id, $this->getStoreId(), $request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
