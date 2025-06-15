<?php

namespace App\Layers\Domain\ValueObject;

use Illuminate\Support\Facades\Hash;

class Password
{
    /**
     * @param string $password
     * @param bool $is_hashed
     */
    private function __construct(
        private readonly string $password,
        private bool $is_hashed,
    ) {
    }

    /**
     * @param string $password
     * @param bool $is_hashed
     * @return Password
     */
    public static function make(
        string $password,
        bool $is_hashed = false,
    ): Password {
        return new Password($password, $is_hashed);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isHashed(): bool
    {
        return $this->is_hashed;
    }

    /**
     * @return string
     */
    public function asHashed(): string
    {
        return $this->is_hashed ? $this->password : Hash::make($this->password);
    }
}
