<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\Shop\ShowUseCase;
use App\Layers\Application\UseCase\Shop\Shop\StoreUseCase;
use App\Layers\Application\UseCase\Shop\Shop\UpdateUseCase;
use App\Layers\Domain\Entity\Shop\ShopEntity;
use App\Layers\Presentation\Requests\Shop\Shop\StoreRequest;
use App\Layers\Presentation\Requests\Shop\Shop\UpdateRequest;
use Illuminate\Http\JsonResponse;

class ShopController extends Controller
{
    /**
     * @param StoreRequest $request
     * @param StoreUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(
        StoreRequest $request,
        StoreUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param ShowUseCase $use_case
     * @return JsonResponse
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function show(
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var ShopEntity $store_entity */
        $store_entity = $output->getData()[0];

        return response()->json([
            'id' => $store_entity->getId(),
            'name' => $store_entity->getName(),
            'category_id' => $store_entity->getCategoryId(),
            'logo' => $store_entity->getLogo()->getCurrentUrl(),
            'postal_code' => $store_entity->getPostalCode(),
            'address' => $store_entity->getAddress(),
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($this->getStoreId(), $request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
