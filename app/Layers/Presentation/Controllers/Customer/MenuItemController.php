<?php

namespace App\Layers\Presentation\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Customer\MenuItem\ListUseCase;
use App\Layers\Application\UseCase\Customer\MenuItem\ShowUseCase;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemOptionEntity;
use App\Layers\Domain\Entity\Shop\MenuItem\MenuItemOptionValueEntity;
use App\Layers\Presentation\Requests\Customer\MenuItem\ListRequest;
use App\Layers\Presentation\Requests\Customer\MenuItem\ShowRequest;
use Illuminate\Http\JsonResponse;

class MenuItemController extends Controller
{
    /**
     * @param ListRequest $request
     * @param ListUseCase $use_case
     * @return JsonResponse
     * @throws \App\Exceptions\DomainException
     */
    public function index(
        ListRequest $request,
        ListUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request);
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
                    'description' => $item->getDescription() ?? '',
                    'category' => $item->getCategory()->getName(),
                    'status' => $item->getStatus()->getName(),
                    'is_option' => $item->getIsOption(),
                    'created_at' => $item->getCreatedAt()?->format('Y-m-d H:i:s'),
                    'updated_at' => $item->getUpdatedAt()?->format('Y-m-d H:i:s'),
                ];
            }),
        ]);
    }

    /**
     * @param int $menu_item_id
     * @param ShowRequest $request
     * @param ShowUseCase $use_case
     * @return JsonResponse
     * @throws \App\Exceptions\DomainException
     */
    public function show(
        int $menu_item_id,
        ShowRequest $request,
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($menu_item_id, $request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var MenuItemEntity $menu_item_entity */
        $menu_item_entity = $output->getData()[0];

        return response()->json([
            'number' => $menu_item_entity->getNumber(),
            'category' => $menu_item_entity->getCategory()->getName(),
            'name' => $menu_item_entity->getName(),
            'price' => $menu_item_entity->getPrice(),
            'image' => $menu_item_entity->getImage()->getCurrentUrl(),
            'description' => $menu_item_entity->getDescription() ?? '',
            'status' => $menu_item_entity->getStatus()->getValue(),
            'options' => $menu_item_entity->getOptionList()->map(function ($item) {
                /** @var MenuItemOptionEntity $item */
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'values' => $item->getOptionValueList()->map(function ($item) {
                        /** @var MenuItemOptionValueEntity $item */
                        return [
                            'id' => $item->getId(),
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
}
