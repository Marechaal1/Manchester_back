<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class UnauthorizedException extends DomainException
{
    public function __construct(string $message = 'Você não tem permissão para realizar esta ação')
    {
        parent::__construct($message, 'UNAUTHORIZED', 403);
    }
}


