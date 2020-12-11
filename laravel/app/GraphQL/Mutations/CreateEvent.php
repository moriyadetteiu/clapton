<?php

namespace App\GraphQL\Mutations;

use App\Models\Event;
use App\Models\EventDate;
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
        $eventData = Arr::except($args, ['directive']);
        
        $event = Event::create(Arr::except($args, ['event_dates']));
        
        $eventDates = $event->eventDates()->createMany($eventData['event_dates']);
        
        $event['event_dates'] = $eventDates;
        return $event;
    }
}
