<?php

namespace App\Layers\Domain\Entity\Shop\Seat;

use App\Exceptions\DomainException;
use App\Layers\Domain\ValueObject\SeatStatus;
use App\Layers\Presentation\Requests\Shop\Seat\UpdateRequest;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SeatFactory
{
    /**
     * @param $request
     * @param $store_id
     * @return SeatEntity
     * @throws DomainException
     */
    public function makeNew($request, $store_id): SeatEntity
    {
        return SeatEntity::make(
            id: null,
            store_id: $store_id,
            number: $request->number,
            order: $request->order,
            status: SeatStatus::make(),
        );
    }

    /**
     * @param $model
     * @return SeatEntity
     * @throws DomainException
     */
    public function makeByModel($model): SeatEntity
    {
        // 最新の座席セッションを取得
        $customer = $this->getStartAtAndEndAt($model);

        return SeatEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            number: $model->number,
            order: $model->order,
            status: SeatStatus::make($model->status),
            qr_code: $model->qr_code,
            start_at: $customer['start_at'],
            end_at: $customer['end_at'],
        );
    }

    /**
     * @param Seat $model
     * @param UpdateRequest $request
     * @return SeatEntity
     * @throws DomainException
     */
    public function makeByModelAndRequest(
        Seat $model,
        UpdateRequest $request
    ): SeatEntity {
        // 最新の座席セッションを取得
        $customer = $this->getStartAtAndEndAt($model);

        return SeatEntity::make(
            id: $model->id,
            store_id: $model->store_id,
            number: $request->input('number'),
            order: $request->input('order'),
            status: SeatStatus::make($request->input('status')),
            qr_code: $model->qr_code,
            start_at: $customer['start_at'],
            end_at: $customer['end_at'],
        );
    }

    /**
     * @param $db_collection
     * @return Collection
     * @throws DomainException
     */
    public function makeListFromDbCollection($db_collection): Collection
    {
        return $db_collection->map(function ($item) {
            return $this->makeByModel($item);
        });
    }

    /**
     * 座席セッションの開始時間と終了時間を取得
     * @param $model
     * @return null[]
     */
    private function getStartAtAndEndAt($model): array
    {
        $last_customer = $model->status !== SeatStatus::VACANT ?
            $model->customers->last() : null;
        $start_at = $last_customer?->start_at;
        $end_at = $last_customer?->end_at;
        if ($start_at !== null) {
            $start_at = Carbon::parse($start_at);
        }
        if ($end_at !== null) {
            $end_at = Carbon::parse($end_at);
        }

        return [
            'start_at' => $start_at,
            'end_at' => $end_at,
        ];
    }
}
