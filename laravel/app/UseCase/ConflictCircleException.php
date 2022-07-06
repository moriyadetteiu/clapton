<?php

namespace App\UseCase;

use Exception;

use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Illuminate\Database\Eloquent\Collection;

class ConflictCircleException extends Exception implements RendersErrorsExtensions
{
    private Collection $circles;
    private Collection $circlePlacements;

    public function __construct(Collection $circles, Collection $circlePlacements, string $message = '')
    {
        $circlePlacements->loadMissing('circle');
        $circles->loadMissing('circlePlacements');

        $this->circles = $circles;
        $this->circlePlacements = $circlePlacements;

        parent::__construct($message);
    }

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'conflictCircle';
    }

    public function extensionsContent(): array
    {
        return [
            'conflicts' => [
                'circles' => $this->circles,
                'circlePlacements' => $this->circlePlacements,
            ]
        ];
    }
}
