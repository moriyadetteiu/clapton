<?php

namespace App\GraphQL\Mutations;

use App\Models\Event;
use Illuminate\Support\Arr;

class CreateEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        $teamData = Arr::except($args, ['directive']);
        $team = Event::create($teamData);
        return $team;
    }
}
