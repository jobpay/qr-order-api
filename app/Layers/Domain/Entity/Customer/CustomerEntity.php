<?php

namespace App\Layers\Domain\Entity\Customer;

use App\Layers\Domain\ValueObject\CustomerStatus;
use App\Layers\Domain\ValueObject\SeatStatus;
use Carbon\Carbon;

class CustomerEntity
{
    /**
     * @param int $id
     * @param int $store_id
     * @param string $store_name
     * @param int $seat_id
     * @param string $seat_number
     * @param string $token
     * @param Carbon $start_at
     * @param Carbon $end_at
     * @param SeatStatus $seat_status
     * @param CustomerStatus $session_status
     * @param int $sum_price
     */
    private function __construct(
        private readonly int               $id,
        private readonly int               $store_id,
        private readonly string            $store_name,
        private readonly int               $seat_id,
        private readonly string            $seat_number,
        private readonly string            $token,
        private readonly Carbon            $start_at,
        private readonly Carbon            $end_at,
        private readonly SeatStatus        $seat_status,
        private readonly CustomerStatus $session_status,
        private readonly int               $sum_price,
    ) {
    }

    /**
     * @param int $id
     * @param int $store_id
     * @param string $store_name
     * @param int $seat_id
     * @param string $seat_number
     * @param string $token
     * @param Carbon $start_at
     * @param Carbon $end_at
     * @param SeatStatus $seat_status
     * @param CustomerStatus $session_status
     * @param int $sum_price
     * @return self
     */
    public static function make(
        int               $id,
        int               $store_id,
        string            $store_name,
        int               $seat_id,
        string            $seat_number,
        string            $token,
        Carbon            $start_at,
        Carbon            $end_at,
        SeatStatus        $seat_status,
        CustomerStatus $session_status,
        int               $sum_price,
    ): self {
        return new self(
            $id,
            $store_id,
            $store_name,
            $seat_id,
            $seat_number,
            $token,
            $start_at,
            $end_at,
            $seat_status,
            $session_status,
            $sum_price,
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * @return string
     */
    public function getStoreName(): string
    {
        return $this->store_name;
    }

    /**
     * @return int
     */
    public function getSeatId(): int
    {
        return $this->seat_id;
    }

    /**
     * @return string
     */
    public function getSeatNumber(): string
    {
        return $this->seat_number;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return Carbon
     */
    public function getStartAt(): Carbon
    {
        return $this->start_at;
    }

    /**
     * @return Carbon
     */
    public function getEndAt(): Carbon
    {
        return $this->end_at;
    }

    /**
     * @return SeatStatus
     */
    public function getSeatStatus(): SeatStatus
    {
        return $this->seat_status;
    }

    /**
     * @return CustomerStatus
     */
    public function getCustomerStatus(): CustomerStatus
    {
        return $this->session_status;
    }

    /**
     * @return int
     */
    public function getSumPrice(): int
    {
        return $this->sum_price;
    }
}
