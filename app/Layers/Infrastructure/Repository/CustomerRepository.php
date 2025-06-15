<?php

namespace App\Layers\Infrastructure\Repository;

use App\Layers\Domain\Entity\Customer\Seat\SeatEntity;
use App\Layers\Domain\ValueObject\CustomerStatus;
use App\Layers\Domain\ValueObject\SeatStatus;
use App\Models\Customer;
use App\Models\Seat;
use App\Models\Store\Store;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{
    /**
     * @param SeatEntity $customer_entity
     * @return void
     * @throws \Exception
     */
    public function create(SeatEntity $customer_entity): void
    {
        DB::beginTransaction();

        try {
            Seat::query()
                ->where('id', $customer_entity->getSeatId())
                ->update(['status' => SeatStatus::ORDER_WAIT]);

            Customer::query()
                ->create([
                    'seat_id' => $customer_entity->getSeatId(),
                    'status' => $customer_entity->getStatus()->getValue(),
                    'token' => $customer_entity->getToken()?->getValue(),
                    'start_at' => now(),
                    'end_at' => now()->addHours(2),
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param SeatEntity $customer_entity
     * @return void
     * @throws \Exception
     */
    public function update(SeatEntity $customer_entity): void
    {
        DB::beginTransaction();

        try {
            Customer::query()
                ->where('id', $customer_entity->getId())
                ->update(['status' => CustomerStatus::CLOSED]);

            Seat::query()
                ->where('id', $customer_entity->getSeatId())
                ->update(['status' => SeatStatus::CHECKOUT_WAIT]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * @param int $customer_id
     * @return Customer|null
     */
    public function find(int $customer_id): ?Customer
    {
        return Customer::find($customer_id);
    }

    /**
     * @param int $seat_id
     * @return Customer|null
     */
    public function findLatestBySeatId(int $seat_id): ?Customer
    {
        return Customer::query()
            ->with(['seat','orders'])
            ->where('seat_id', $seat_id)
            ->where('status', '<>', CustomerStatus::PRESENT)
            ->latest()
            ->first();
    }

    /**
     * @param string $token
     * @return Store|null
     */
    public function findWithSeatAndOrdersByToken(string $token): ?Customer
    {
        return Customer::query()
            ->with(['seat','orders'])
            ->where('token', $token)
            ->first();
    }
}
