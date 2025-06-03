<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\MenuItem\DestroyUseCase;
use App\Layers\Application\UseCase\Shop\MenuItem\ListUseCase;
use App\Layers\Application\UseCase\Shop\MenuItem\ShowUseCase;
use App\Layers\Application\UseCase\Shop\MenuItem\StoreUseCase;
use App\Layers\Application\UseCase\Shop\MenuItem\UpdateUseCase;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemOptionEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemOptionValueEntity;
use App\Layers\Presentation\Requests\Shop\MenuItem\ListRequest;
use App\Layers\Presentation\Requests\Shop\MenuItem\StoreRequest;
use App\Layers\Presentation\Requests\Shop\MenuItem\UpdateRequest;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    /**
     * @param ListRequest $request
     * @param ListUseCase $use_case
     * @return JsonResponse
     * @throws \Illuminate\Auth\AuthenticationException
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
            'menu_items' => $output->getData()[0]->map(function ($item) {
                /** @var MenuItemEntity $item */
                return [
                    'id' => $item->getId(),
                    'number' => $item->getNumber(),
                    'name' => $item->getName(),
                    'image' => $item->getImage()->getCurrentUrl(),
                    'category' => $item->getCategory()->getName(),
                    'price' => $item->getPrice(),
                    'status' => $item->getStatus()->getName(),
                    'is_option' => $item->getIsOption(),
                    'created_at' => $item->getCreatedAt()?->format('Y/m/d'),
                    'updated_at' => $item->getUpdatedAt()?->format('Y/m/d'),
                ];
            }),
            'total' => $output->getData()[1],
        ]);
    }

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
        $output = $use_case->exec($request, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $menu_item_id
     * @param ShowUseCase $use_case
     * @return JsonResponse
     */
    public function show(
        int $menu_item_id,
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($menu_item_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var MenuItemEntity $menu_item_entity */
        $menu_item_entity = $output->getData()[0];

        return response()->json([
            'number' => $menu_item_entity->getNumber(),
            'category' => $menu_item_entity->getCategory()->getId(),
            'name' => $menu_item_entity->getName(),
            'price' => $menu_item_entity->getPrice(),
            'image' => $menu_item_entity->getImage()->getCurrentUrl(),
            'description' => $menu_item_entity->getDescription(),
            'status' => $menu_item_entity->getStatus()->getValue(),
            'options' => $menu_item_entity->getOptionList()->map(function ($item) {
                /** @var MenuItemOptionEntity $item */
                return [
                    'name' => $item->getName(),
                    'values' => $item->getOptionValueList()->map(function ($item) {
                        /** @var MenuItemOptionValueEntity $item */
                        return [
                            'order' => $item->getOrder(),
                            'name' => $item->getName(),
                            'cost' => $item->getCost(),
                        ];
                    }),
                ];
            }),
            'created_at' => $menu_item_entity->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $menu_item_entity->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param int $menu_item_id
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        int $menu_item_id,
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($menu_item_id, $this->getStoreId(), $request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $menu_item_id
     * @param DestroyUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(
        int $menu_item_id,
        DestroyUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($menu_item_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
