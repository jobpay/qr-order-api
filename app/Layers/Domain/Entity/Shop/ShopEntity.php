<?php

namespace App\Layers\Domain\Entity\Shop;
use App\Layers\Domain\ValueObject\Image\StoreImage;
use Carbon\Carbon;

class ShopEntity
{
    /**
     * @param int|null $id
     * @param string $name
     * @param int $category_id
     * @param string|null $description
     * @param StoreImage|null $logo
     * @param int|null $postal_code
     * @param string|null $address
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @param Carbon|null $deleted_at
     */
    private function __construct(
        private readonly ?int        $id,
        private readonly string      $name,
        private readonly int         $category_id,
        private readonly ?string     $description,
        private readonly ?StoreImage $logo,
        private readonly ?int        $postal_code,
        private readonly ?string     $address,
        private readonly ?Carbon     $created_at,
        private readonly ?Carbon     $updated_at,
        private readonly ?Carbon     $deleted_at,
    ) {
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param int $category_id ,
     * @param string|null $description
     * @param StoreImage|null $logo
     * @param int|null $postal_code
     * @param string|null $address
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @param Carbon|null $deleted_at
     * @return self
     */
    public static function make(
        ?int        $id,
        string      $name,
        int         $category_id,
        ?string     $description,
        ?StoreImage $logo,
        ?int        $postal_code,
        ?string     $address,
        ?Carbon     $created_at = null,
        ?Carbon     $updated_at = null,
        ?Carbon     $deleted_at = null,
    ): self {
        return new self(
            id: $id,
            name: $name,
            category_id: $category_id,
            description: $description,
            logo: $logo,
            postal_code: $postal_code,
            address: $address,
            created_at: $created_at,
            updated_at: $updated_at,
            deleted_at: $deleted_at,
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return StoreImage|null
     */
    public function getLogo(): ?StoreImage
    {
        return $this->logo;
    }

    /**
     * @return int|null
     */
    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
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
     * @return Carbon|null
     */
    public function getDeletedAt(): ?Carbon
    {
        return $this->deleted_at;
    }

    /**
     * @param ShopEntity $store
     * @return bool
     */
    public function isOtherStore(ShopEntity $store): bool
    {
        return $this->id !== $store->getId();
    }
}
