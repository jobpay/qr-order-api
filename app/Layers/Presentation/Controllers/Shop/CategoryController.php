<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\Category\DestroyUseCase;
use App\Layers\Application\UseCase\Shop\Category\ListUseCase;
use App\Layers\Application\UseCase\Shop\Category\StoreUseCase;
use App\Layers\Application\UseCase\Shop\Category\UpdateUseCase;
use App\Layers\Domain\Entity\Category\CategoryEntity;
use App\Layers\Presentation\Requests\Shop\Category\ListRequest;
use App\Layers\Presentation\Requests\Shop\Category\StoreRequest;
use App\Layers\Presentation\Requests\Shop\Category\UpdateRequest;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
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
            'categories' => $output->getData()[0]->map(function ($item) {
                /** @var CategoryEntity $item */
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'order' => $item->getOrder(),
                    'created_at' => $item->getCreatedAt()?->format('Y-m-d H:i:s'),
                    'updated_at' => $item->getUpdatedAt()?->format('Y-m-d H:i:s'),
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
     * @param int $category_id
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        int $category_id,
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request, $category_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $category_id
     * @param DestroyUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(
        int $category_id,
        DestroyUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($category_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
