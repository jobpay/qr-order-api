<?php

namespace App\Layers\Domain\Entity\Shop\Order;

use App\Layers\Domain\ValueObject\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrderEntity
{
    /**
     * @param int|null $id
     * @param int $store_id
     * @param string $seat_number
     * @param OrderStatus $status
     * @param string $name
     * @param Collection $options
     * @param int $quantity
     * @param int $price
     * @param Carbon|null $created_at
     */
    private function __construct(
        private readonly ?int               $id,
        private readonly int                $store_id,
        private readonly string             $seat_number,
        private readonly OrderStatus        $status,
        private readonly string             $name,
        private readonly Collection         $options,
        private readonly int                $quantity,
        private readonly int                $price,
        private readonly ?Carbon            $created_at,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $store_id
     * @param string $seat_number
     * @param OrderStatus $status
     * @param string $name
     * @param Collection $options
     * @param int $quantity
     * @param int $price
     * @param Carbon|null $created_at
     * @return self
     */
    public static function make(
        ?int               $id,
        int                $store_id,
        string             $seat_number,
        OrderStatus        $status,
        string             $name,
        Collection         $options,
        int                $quantity,
        int                $price,
        ?Carbon            $created_at,
    ): self {
        return new self(
            $id,
            $store_id,
            $seat_number,
            $status,
            $name,
            $options,
            $quantity,
            $price,
            $created_at,
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
    public function getSeatNumber(): string
    {
        return $this->seat_number;
    }

    /**
     * @return OrderStatus
     */
    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    /**
     * @param int $store_id
     * @return bool
     */
    public function isOtherStore(int $store_id): bool
    {
        return $this->getStoreId() !== $store_id;
    }
}
