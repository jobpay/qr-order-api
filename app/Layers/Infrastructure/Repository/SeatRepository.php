<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Shop\Seat\SeatEntity;
use App\Layers\Domain\ValueObject\SeatStatus;
use App\Layers\Presentation\Requests\Shop\Seat\ListRequest;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Collection;

class SeatRepository
{
    /**
     * @param ListRequest $request
     * @param int $store_id
     * @return Collection
     */
    public function get(
        ListRequest $request,
        int $store_id
    ): Collection {
        return Seat::query()
            ->with('customers')
            ->where('store_id', $store_id)
            ->limit($request->input('limit'))
            ->offset($request->input('offset'))
            ->orderBy('order')
            ->get();
    }

    /**
     * @param int $store_id
     * @return int
     */
    public function getTotal(
        int $store_id
    ): int {
        return Seat::query()
            ->with('customers')
            ->where('store_id', $store_id)
            ->count();
    }

    /**
     * @param SeatEntity $seat_entity
     * @return Seat
     */
    public function create(SeatEntity $seat_entity): Seat
    {
        return Seat::create([
            'store_id' => $seat_entity->getStoreId(),
            'number' => $seat_entity->getNumber(),
            'order' => $seat_entity->getOrder(),
            'qr_code' => '', // seat_idが確定してからQRコードを生成して格納するので空文字で初期化
        ]);
    }

    /**
     * @param SeatEntity $seat_entity
     * @return void
     */
    public function updateQrCode(SeatEntity $seat_entity): void
    {
        Seat::query()
            ->where('id', $seat_entity->getId())
            ->update([
                'qr_code' => $seat_entity->generateQrCode(),
            ]);
    }

    /**
     * @param SeatEntity $seat_entity
     * @return void
     */
    public function update(SeatEntity $seat_entity): void
    {
        Seat::query()
            ->where('id', $seat_entity->getId())
            ->update([
                'number' => $seat_entity->getNumber(),
                'order' => $seat_entity->getOrder(),
                'status' => $seat_entity->getStatus()->getValue(),
            ]);
    }

    /**
     * @param int $seat_id
     * @return void
     */
    public function updateVacant(int $seat_id): void
    {
        Seat::query()
            ->where('id', $seat_id)
            ->update([
                'status' => SeatStatus::VACANT,
            ]);
    }

    /**
     * @param SeatEntity $seat_entity
     * @return void
     */
    public function delete(SeatEntity $seat_entity): void
    {
        Seat::query()->where('id', $seat_entity->getId())->delete();
    }

    /**
     * @param int $seat_id
     * @return Seat|null
     */
    public function find(int $seat_id): ?Seat
    {
        return Seat::find($seat_id);
    }

    /**
     * @param int $seat_id
     * @return Seat|null
     */
    public function findWithStoreAndSession(int $seat_id): ?Seat
    {
        return Seat::where('id', $seat_id)
            ->with(['store', 'customers'])
            ->first();
    }
}
