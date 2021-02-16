<?php

namespace App\UseCase\JoinEvent;

use App\Models\JoinEvent;
use App\UseCase\UseCase;

class CreateJoinEvent extends UseCase
{
    public function execute(CreateJoinEventInput $input)
    {
        $joinEventData = $input->getJoinEventData();
        $joinEvent = JoinEvent::create($joinEventData);

        $eventDates = $input->getJoinEventDates();
        $joinEvent->joinEventDates()->createMany($eventDates);

        $joinEvent->refresh();

        return $joinEvent;
    }
}
