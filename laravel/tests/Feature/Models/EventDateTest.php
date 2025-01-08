<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Event;
use App\Models\EventDate;
use Str;
use Tests\TestCase;

class EventDateTest extends TestCase
{
    use WithFaker;

    public function testOnSavedObserver()
    {
        $event = Event::factory()->hasEventDates(3)->create([
            'is_progress' => false,
        ]);
        $latestEventDate = $event->eventDates()->latest('date')->first();

        $this->travelTo($latestEventDate->date->copy()->addDay());
        EventDate::factory()->create([
            'event_id' => $event->id,
            'date' => $latestEventDate->date,
        ]);
        $event->refresh();
        $this->assertFalse($event->is_progress);

        $this->travelTo($latestEventDate->date->copy()->addDay());
        EventDate::factory()->create([
            'event_id' => $event->id,
            'date' => $latestEventDate->date->copy()->addDay(),
        ]);
        $event->refresh();
        $this->assertTrue($event->is_progress);
    }

    public function testAttributes()
    {
        $eventDate = EventDate::factory()->create();
        $this->assertTrue(Str::startsWith($eventDate->full_format_date, $eventDate->name));
    }
}
