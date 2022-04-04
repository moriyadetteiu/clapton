<?php

namespace Database\DatasetFactories;

use App\Models\{
    Circle,
    CirclePlacement,
    CircleProduct,
    CareAboutCircle,
};

class CircleDatasetFactory
{
    private $numberOfCreate = 20;

    public function one(): self
    {
        $this->numberOfCreate = null;
        return $this;
    }

    public function create(): array
    {
        $eventDataset = (new EventDatasetFactory())->create();
        $event = $eventDataset['event'];
        $joinEvent = $eventDataset['joinEvent'];
        $circlePlacementFactory = CirclePlacement::factory()
            ->state(['event_date_id' => $event->eventDates->random()->id])
            ->hasCircleProducts(1);
        $circle = Circle::factory($this->numberOfCreate)
            ->has($circlePlacementFactory)
            ->hasCareAboutCircles(1, [
                'join_event_id' => $joinEvent->id,
            ])
            ->create();
        return array_merge($eventDataset, compact('circle'));
    }
}
