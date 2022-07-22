<?php

namespace App\UseCase;

use Exception;

use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Illuminate\Database\Eloquent\Collection;

class ConflictCircleException extends Exception implements RendersErrorsExtensions
{
    private Collection $circlePlacements;

    public function __construct(Collection $circlePlacements, string $message = '')
    {
        $circlePlacements->loadMissing('circle');
        $circlePlacements->append('formatted_placement');

        $this->circlePlacements = $circlePlacements->values();

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
            'conflicts' =>  $this->circlePlacements,
        ];
    }
}
