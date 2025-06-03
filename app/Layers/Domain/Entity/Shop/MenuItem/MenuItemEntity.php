<?php

namespace App\Layers\Domain\Entity\Shop\MenuItem;

use App\Layers\Domain\ValueObject\Image\MenuItemImage;
use App\Layers\Domain\ValueObject\MenuItemStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MenuItemEntity
{
    /**
     * @param int|null $id
     * @param int $store_id
     * @param int $number
     * @param string $name
     * @param int $price
     * @param string|null $description
     * @param MenuItemImage $image
     * @param MenuCategoryEntity $category
     * @param MenuItemStatus $status
     * @param Collection $option_list
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     */
    private function __construct(
        private readonly ?int               $id,
        private readonly int                $store_id,
        private readonly int                $number,
        private readonly string             $name,
        private readonly int                $price,
        private readonly ?string            $description,
        private readonly MenuItemImage      $image,
        private readonly MenuCategoryEntity $category,
        private readonly MenuItemStatus     $status,
        private readonly Collection         $option_list,
        private readonly ?Carbon            $created_at,
        private readonly ?Carbon            $updated_at,
    ) {
    }

    /**
     * @param int|null $id
     * @param int $store_id
     * @param int $number
     * @param string $name
     * @param int $price
     * @param string|null $description
     * @param MenuItemImage $image
     * @param MenuCategoryEntity $category
     * @param MenuItemStatus $status
     * @param Collection $option_list
     * @param Carbon|null $created_at
     * @param Carbon|null $updated_at
     * @return self
     */
    public static function make(
        ?int               $id,
        int                $store_id,
        int                $number,
        string             $name,
        int                $price,
        ?string            $description,
        MenuItemImage      $image,
        MenuCategoryEntity $category,
        MenuItemStatus     $status,
        Collection         $option_list,
        ?Carbon            $created_at,
        ?Carbon            $updated_at,
    ): self {
        return new self(
            id: $id,
            store_id: $store_id,
            number: $number,
            name: $name,
            price: $price,
            description: $description,
            image: $image,
            category: $category,
            status: $status,
            option_list: $option_list,
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
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
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
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return MenuItemImage
     */
    public function getImage(): MenuItemImage
    {
        return $this->image;
    }

    /**
     * @return MenuCategoryEntity
     */
    public function getCategory(): MenuCategoryEntity
    {
        return $this->category;
    }

    /**
     * @return MenuItemStatus
     */
    public function getStatus(): MenuItemStatus
    {
        return $this->status;
    }

    /**
     * @return Collection
     */
    public function getOptionList(): Collection
    {
        return $this->option_list;
    }

    /**
     * @return bool
     */
    public function getIsOption(): bool
    {
        return $this->getOptionList()->isNotEmpty();
    }

    /**
     * @return ?Carbon
     */
    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    /**
     * @return ?Carbon
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
