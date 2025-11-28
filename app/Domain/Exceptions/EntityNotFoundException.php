<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

class EntityNotFoundException extends DomainException
{
    public function __construct(string $entityName, string $identifier = '')
    {
        $message = $identifier
            ? "{$entityName} não encontrado(a) com identificador: {$identifier}"
            : "{$entityName} não encontrado(a)";
        
        parent::__construct($message, 'ENTITY_NOT_FOUND', 404);
    }
}


