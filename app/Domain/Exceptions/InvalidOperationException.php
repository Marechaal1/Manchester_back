<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class InvalidOperationException extends DomainException
{
    public function __construct(string $message = 'Operação inválida')
    {
        parent::__construct($message, 'INVALID_OPERATION', 400);
    }
}


