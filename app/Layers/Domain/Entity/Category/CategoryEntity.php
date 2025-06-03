<?php

namespace App\Layers\Domain\Entity\Category;

use Carbon\Carbon;

class CategoryEntity
{
    /**
     * @param int|null $id
     * @param int $store_id
     * @param string $name
     * @param int $order
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     */
    private function __construct(
        private readonly ?int    $id,
        private readonly int     $store_id,
        private readonly string  $name,
        private readonly int     $order,
        private readonly ?Carbon $created_at,
        private readonly ?Carbon $updated_at,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $store_id
     * @param string $name
     * @param int $order
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @return CategoryEntity
     */
    public static function make(
        ?int    $id,
        int     $store_id,
        string  $name,
        int     $order,
        ?Carbon $created_at,
        ?Carbon $updated_at,
    ): CategoryEntity {
        return new self(
            id: $id,
            store_id: $store_id,
            name: $name,
            order: $order,
            created_at: $created_at,
            updated_at: $updated_at,
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
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    /**
     * @return Carbon|null
     */
    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }

    /**
     * @param int $store_id
     * @return bool
     */
    public function isOtherStore(int $store_id): bool
    {
        return $this->store_id !== $store_id;
    }
}
