<?php

namespace App\Layers\Domain\Entity\Customer\Seat;

use App\Layers\Domain\Entity\Shop\ShopEntity;
use App\Layers\Domain\ValueObject\CustomerStatus;
use App\Layers\Domain\ValueObject\CustomerToken;

class SeatEntity
{
    /**
     * @param int|null $id
     * @param int $seat_id
     * @param string $seat_number
     * @param ShopEntity $store
     * @param CustomerStatus $status
     * @param CustomerToken|null $token
     */
    private function __construct(
        private readonly ?int           $id,
        private readonly int            $seat_id,
        private readonly string         $seat_number,
        private readonly ShopEntity     $store,
        private readonly CustomerStatus $status,
        private ?CustomerToken          $token,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $seat_id
     * @param string $seat_number
     * @param ShopEntity $store
     * @param CustomerStatus $status
     * @param CustomerToken|null $token
     * @return self
     */
    public static function make(
        ?int           $id,
        int            $seat_id,
        string         $seat_number,
        ShopEntity     $store,
        CustomerStatus $status,
        ?CustomerToken $token,
    ): self {
        return new self(
            id: $id,
            seat_id: $seat_id,
            seat_number: $seat_number,
            store: $store,
            status: $status,
            token: $token,
        );
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getSeatId(): int
    {
        return $this->seat_id;
    }

    /**
     * @return ShopEntity
     */
    public function getStore(): ShopEntity
    {
        return $this->store;
    }

    /**
     * @return string
     */
    public function getSeatNumber(): string
    {
        return $this->seat_number;
    }

    /**
     * @return CustomerStatus
     */
    public function getStatus(): CustomerStatus
    {
        return $this->status;
    }

    /**
     * @return CustomerToken|null
     */
    public function getToken(): ?CustomerToken
    {
        return $this->token;
    }
}
