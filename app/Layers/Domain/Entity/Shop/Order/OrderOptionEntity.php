<?php

namespace App\Layers\Domain\Entity\Shop\Order;

class OrderOptionEntity
{
    /**
     * @param int|null $id
     * @param string $name
     * @param string $value
     */
    private function __construct(
        private readonly ?int   $id,
        private readonly string $name,
        private readonly string $value,
    ) {
    }

    /**
     * @param int|null $id
     * @param string $name
     * @param string $value
     * @return self
     */
    public static function make(
        ?int               $id,
        string             $name,
        string             $value,
    ): self {
        return new self(
            $id,
            $name,
            $value,
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
