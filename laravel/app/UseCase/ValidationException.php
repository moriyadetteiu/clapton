<?php

namespace App\UseCase;

use Exception;

use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Illuminate\Support\MessageBag;

class ValidationException extends Exception implements RendersErrorsExtensions
{
    private MessageBag $messageBag;

    public function __construct(string $message, MessageBag $messageBag)
    {
        parent::__construct($message);

        $this->messageBag = $messageBag;
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'validationError';
    }

    public function extensionsContent(): array
    {
        return [
            'errors' => $this->messageBag->toArray()
        ];
    }
}
