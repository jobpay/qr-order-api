<?php

namespace App\Exceptions;

class DomainException extends Exception
{
    /**
     * @param array $error_messages
     */
    public function __construct(private readonly array $error_messages)
    {
        parent::__construct($error_messages);
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->error_messages;
    }
}
