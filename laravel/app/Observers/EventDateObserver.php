<?php

namespace App\Observers;

use App\Models\EventDate;

class EventDateObserver
{
    public function saved(EventDate $eventDate)
    {
        $eventDate->event->updateIsProgress();
    }
}
