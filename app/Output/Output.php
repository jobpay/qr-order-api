<?php

namespace App\Output;

use Illuminate\Support\Collection;

class Output
{
    /**
     * @param array|null $data
     * @param array $errors
     */
    public function __construct(
        private readonly ?array $data = null,
        private readonly array $errors = []
    ) {
    }

    /**
     * @return array|null
     */
    public function getData(): array|null
    {
        return $this->data;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return ['errors' => $this->errors];
    }
}
