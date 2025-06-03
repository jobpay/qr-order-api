<?php

namespace App\Layers\Domain\Entity\Shop\MenuItem;

class MenuCategoryEntity
{
    /**
     * @param int $id
     * @param string $name
     */
    private function __construct(
        private readonly int $id,
        private readonly string $name,
    ) {
    }

    /**
     * @param int $id
     * @param string $name
     * @return self
     */
    public static function make(int $id, string $name): self
    {
        return new self($id, $name);
    }

    /**
     * @return int
     */
    public function getId(): int
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
}
