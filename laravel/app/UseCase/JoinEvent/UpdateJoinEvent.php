<?php

namespace App\UseCase\JoinEvent;

use App\Models\JoinEvent;
use App\UseCase\UseCase;

class UpdateJoinEvent extends UseCase
{
    public function execute(UpdateJoinEventInput $input)
    {
        $joinEventData = $input->getJoinEventData();
        $joinEvent = JoinEvent::findOrFail($input->getId());
        $joinEvent->update($joinEventData);

        $joinEvent->joinEventDates->each(fn ($joinEvent) => $joinEvent->delete());
        $eventDates = $input->getJoinEventDates();
        $joinEvent->joinEventDates()->createMany($eventDates);

        $joinEvent->refresh();
        return $joinEvent;
    }
}
