<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use Exception;

abstract class DomainException extends Exception
{
    protected string $errorCode;

    public function __construct(string $message = '', string $errorCode = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode ?: static::class;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}


