<?php

namespace App\Layers\Presentation\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Shop\User\DestroyUseCase;
use App\Layers\Application\UseCase\Shop\User\ListUseCase;
use App\Layers\Application\UseCase\Shop\User\ShowUseCase;
use App\Layers\Application\UseCase\Shop\User\StoreUseCase;
use App\Layers\Application\UseCase\Shop\User\UpdateUseCase;
use App\Layers\Domain\Entity\Shop\User\UserEntity;
use App\Layers\Presentation\Requests\Shop\User\ListRequest;
use App\Layers\Presentation\Requests\Shop\User\StoreRequest;
use App\Layers\Presentation\Requests\Shop\User\UpdateRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
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
            'users' => $output->getData()[0]->map(function ($item) {
                /** @var UserEntity $item */
                return [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'email' => $item->getEmail(),
                    'role' => $item->getRole()->getName(),
                ];
            })
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
     * @param int $user_id
     * @param ShowUseCase $use_case
     * @return JsonResponse
     */
    public function show(
        int $user_id,
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($user_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var UserEntity $user_entity */
        $user_entity = $output->getData()[0];

        return response()->json([
            'id' => $user_entity->getId(),
            'name' => $user_entity->getName(),
            'email' => $user_entity->getEmail(),
            'role' => [
                'id' => $user_entity->getRole()->getId(),
                'name' => $user_entity->getRole()->getName(),
            ],
        ]);
    }

    /**
     * @param int $user_id
     * @param UpdateRequest $request
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        int $user_id,
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($user_id, $this->getStoreId(), $request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }

    /**
     * @param int $user_id
     * @param DestroyUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(
        int $user_id,
        DestroyUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($user_id, $this->getStoreId());
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        return response()->json();
    }
}
