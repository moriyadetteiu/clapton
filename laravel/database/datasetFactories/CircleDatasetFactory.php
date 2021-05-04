<?php

namespace Database\DatasetFactories;

use App\Models\{
    Circle,
};

class CircleDatasetFactory
{
    public function create()
    {
        $eventDataset = (new EventDatasetFactory())->create();
        $event = $eventDataset['event'];
        $joinEvent = $eventDataset['joinEvent'];
        $circle = Circle::factory(20)
            ->hasCirclePlacements(1, [
                'event_date_id' => $event->eventDates->random()->id,
            ])
            ->hasCareAboutCircles(1, [
                'join_event_id' => $joinEvent->id,
            ])
            ->create();
        return array_merge($eventDataset, compact('circle'));
    }
}
