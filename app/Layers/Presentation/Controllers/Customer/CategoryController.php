<?php

namespace App\Layers\Presentation\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Customer\Category\ListUseCase;
use App\Layers\Domain\Entity\Category\CategoryEntity;
use App\Layers\Presentation\Requests\Customer\Category\ListRequest;
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
        $output = $use_case->exec($request);
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
}
