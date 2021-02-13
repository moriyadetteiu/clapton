<?php

namespace App\UseCase\Event;

use App\Models\Event;
use App\UseCase\UseCase;

class CreateEvent extends UseCase
{
    public function execute(CreateEventInput $input)
    {
        $eventData = $input->getEventData();
        $event = Event::create($eventData);

        $event->eventDates()->createMany($input->getEventDates());

        $event->eventAffiliationTeams()->create([
            'team_id' => $input->getTeamId(),
        ]);

        //        $event->refresh();

        return $event;
    }
}
