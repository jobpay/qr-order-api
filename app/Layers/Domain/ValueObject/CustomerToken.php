<?php

namespace App\Layers\Domain\ValueObject;

use App\Exceptions\DomainException;

class CustomerToken
{
    /**
     * @param string $token
     */
    private function __construct(
        private string $token,
    ) {
    }

    /**
     * @param string|null $token
     * @param int|null $seat_id
     * @return self
     * @throws DomainException
     */
    public static function make(?string $token=null, ?int $seat_id=null): self
    {
        if (is_null($token) && is_null($seat_id)) {
            throw new DomainException('tokenが未指定の場合はseat_idの指定が必要です');
        }

        if (is_null($token)) {
            // seat_idを元にtokenを生成
            $token = md5($seat_id . time());
        }
        return new self($token);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->token;
    }
}
