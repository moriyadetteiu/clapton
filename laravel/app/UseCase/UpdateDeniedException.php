<?php

namespace App\UseCase;

use Exception;

use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

class UpdateDeniedException extends Exception implements RendersErrorsExtensions
{
    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'updateDenied';
    }

    public function extensionsContent(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
