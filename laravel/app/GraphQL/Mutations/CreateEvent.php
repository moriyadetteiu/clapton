<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use App\UseCase\Event\CreateEvent as CreateEventUseCase;
use App\UseCase\Event\CreateEventInput;

class CreateEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        $eventData = Arr::except($args, ['directive']);
        $user = Auth::user();
        $input = new CreateEventInput($eventData);
        $event = (new CreateEventUseCase())->execute($input);
        return $event;
    }
}
