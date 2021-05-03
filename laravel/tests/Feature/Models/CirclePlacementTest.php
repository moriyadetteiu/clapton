<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\Event;
use App\Models\EventDate;

class CirclePlacementTest extends TestCase
{
    use WithFaker;

    public function testScopeInEvent()
    {
        $events = Event::factory(2)->create();
        $events->transform(function ($event) {
            $eventDates = EventDate::factory(3)->create(['event_id' => $event->id]);
            $event->setRelations(['eventDates' => $eventDates]);
            return $event;
        });
        $circle = Circle::factory()->create();
        $circlePlacements = $events->map(function ($event) use ($circle) {
            return CirclePlacement::factory()->create([
                'circle_id' => $circle->id,
                'event_date_id' => $event->eventDates->random()->id,
            ]);
        });

        $circlePlacements->each(function ($circlePlacement) {
            $eventId = $circlePlacement->eventDate->event_id;
            $foundCirclePlacement = CirclePlacement::inEvent($eventId)->firstOrFail();
            $this->assertEquals($circlePlacement->id, $foundCirclePlacement->id);
        });
    }
}
