<?php

namespace App\Layers\Presentation\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Layers\Application\UseCase\Customer\Seat\ShowUseCase;
use App\Layers\Application\UseCase\Customer\Seat\StoreUseCase;
use App\Layers\Application\UseCase\Customer\Seat\UpdateUseCase;
use App\Layers\Domain\Entity\Customer\Seat\SeatEntity;
use App\Layers\Presentation\Requests\Customer\Seat\UpdateRequest;
use Illuminate\Http\JsonResponse;

class SeatController extends Controller
{
    /**
     * QRコード読み取り時に呼びだされるAPI
     *
     * @param int $seat_id
     * @param ShowUseCase $use_case
     * @return JsonResponse
     */
    public function show(
        int $seat_id,
        ShowUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($seat_id);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }

        /** @var SeatEntity $customer_entity */
        $customer_entity = $output->getData()[0];

        return response()->json([
            'seat_id' => $customer_entity->getSeatId(),
            'store_name' => $customer_entity->getStore()->getName(),
            'store_image' => $customer_entity->getStore()->getLogo()->getCurrentUrl(),
            'seat_number' => $customer_entity->getSeatNumber(),
            'is_present' => $customer_entity->getStatus()->isPresent(),
        ]);
    }

    /**
     * 入店処理
     * 完了時にブラウザのクッキーにトークンをセットする
     *
     * @param int $seat_id
     * @param StoreUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(
        int $seat_id,
        StoreUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($seat_id);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }
        // クッキーにトークンをセットして返却
        $customer_entity = $output->getData()[0];
        return response()->json()
            ->cookie('qr_customer_token', $customer_entity->getToken()->getValue());
    }

    /**
     * 退店処理
     *  完了時にブラウザのクッキーに保持しているトークンを破棄する
     *
     * @param UpdateUseCase $use_case
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(
        UpdateRequest $request,
        UpdateUseCase $use_case,
    ): JsonResponse {
        $output = $use_case->exec($request);
        if ($output->isError()) {
            return response()->json($output->getErrors(), 400);
        }
        // customer_tokenをキーごと削除
        return response()->json()
            ->cookie('qr_customer_token', '', -1);
    }
}
