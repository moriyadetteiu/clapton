<?php

namespace Database\DatasetFactories;

use App\Models\{
    Circle,
    CirclePlacement,
    CircleProduct,
    CareAboutCircle,
    WantCircleProduct,
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
            ->hasCircleProducts(1)
            ->hasCareAboutCircles(1, [
                'join_event_id' => $joinEvent->id,
            ]);
        $circle = Circle::factory($this->numberOfCreate)
            ->has($circlePlacementFactory)
            ->create();
        $circle->load([
            'circlePlacements.careAboutCircles'
        ]);
        $circlePlacements = $circle instanceof Circle ? $circle->circlePlacements : $circle->flatMap(fn ($circle) => $circle->circlePlacements);
        $careAboutCircle = $circlePlacements
            ->flatMap(fn ($circlePlacement) => $circlePlacement->careAboutCircles);
        $circleProducts = $circlePlacements
            ->flatMap(fn ($circlePlacement) => $circlePlacement->circleProducts);

        $wantCircleProducts = $circlePlacements
            ->map(function ($circlePlacement) {
                $careAboutCircle = $circlePlacement->careAboutCircles->first();
                $circleProduct = $circlePlacement->circleProducts->first();

                return WantCircleProduct::factory([
                    'care_about_circle_id' => $careAboutCircle->id,
                    'circle_product_id' => $circleProduct->id,
                ])->create();
            });

        return array_merge($eventDataset, compact(
            'circle',
            'circlePlacements',
            'circleProducts',
            'wantCircleProducts'
        ));
    }
}
