<?php

namespace App\Layers\Domain\Entity\Shop\MenuItem;

use Illuminate\Support\Collection;

class MenuItemOptionEntity
{
    /**
     * @param int|null $id
     * @param string $name
     * @param Collection $option_value_list
     */
    private function __construct(
        private readonly ?int       $id,
        private readonly string     $name,
        private readonly Collection $option_value_list,
    ) {
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param Collection $option_value_list
     * @return self
     */
    public static function make(
        ?int       $id,
        string     $name,
        Collection $option_value_list,
    ): self {
        return new self(
            id: $id,
            name: $name,
            option_value_list: $option_value_list,
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
     * @return Collection
     */
    public function getOptionValueList(): Collection
    {
        return $this->option_value_list;
    }
}
