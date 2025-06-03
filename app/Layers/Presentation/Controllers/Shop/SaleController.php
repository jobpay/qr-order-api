<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\Sale\DestroyUseCase;
use App\Layers\Application\UseCase\Shop\Sale\ListUseCase;
use App\Layers\Application\UseCase\Shop\Sale\StoreUseCase;
use App\Layers\Application\UseCase\Shop\Sale\UpdateUseCase;
use App\Layers\Domain\Entity\Shop\Sale\SaleEntity;
use App\Layers\Domain\Entity\Shop\Sale\SaleEntityList;
use App\Layers\Presentation\Requests\Shop\Sale\ListRequest;
use App\Layers\Presentation\Requests\Shop\Sale\StoreRequest;
use App\Layers\Presentation\Requests\Shop\Sale\UpdateRequest;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
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

        /** @var SaleEntityList $sale_list */
        $sale_list = $output->getData()[0];
        $sum_amount = $output->getData()[1];

        return response()->json([
            'sales' => $sale_list->get()->map(function ($item) {
                /** @var SaleEntity $item */
                return [
                    'id' => $item->getId(),
                    'item_name' => $item->getMenuItem()->getName(),
                    'seat_number' => $item->getOrder()->getSeatNumber(),
                    'paid_at' => $item->getPaidAt()->format('Y/m/d H:i'),
                    'amount' => $item->getAmount(),
                ];
            }),
            'sum_price' => $sum_amount,
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
     * @param int $sale_id
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     */
    public function update(
        int $sale_id,
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($sale_id, $request, $this->getStoreId());

        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $sale_id
     * @param DestroyUseCase $use_case
     * @return JsonResponse
     */
    public function destroy(
        int $sale_id,
        DestroyUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($sale_id, $this->getStoreId());

        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
