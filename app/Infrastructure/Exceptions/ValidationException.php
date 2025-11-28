<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Domain\Exceptions\DomainException;

class ValidationException extends DomainException
{
    private array $errors;

    public function __construct(string $message = 'Dados inválidos', array $errors = [])
    {
        parent::__construct($message, 'VALIDATION_ERROR', 422);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}


