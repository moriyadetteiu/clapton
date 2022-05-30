<?php

namespace Tests\Feature\Models;

use ReflectionClass;

use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Event;
use Tests\TestCase;

class EventTest extends TestCase
{
    use WithFaker;

    public function testJudgeIsProgress()
    {
        $event = Event::factory()->hasEventDates(3)->create();
        $latestEventDate = $event->eventDates()->latest('date')->first();

        $reflectionClass = new ReflectionClass($event);
        $reflectionMethod = $reflectionClass->getMethod('judgeIsProgress');
        $reflectionMethod->setAccessible(true);

        // 同日
        $this->travelTo($latestEventDate->date);
        $this->assertTrue($reflectionMethod->invoke($event));

        // 同日の昼間などはまだ同じ日として扱う
        $this->travelTo($latestEventDate->date->copy()->addMinute(12));
        $this->assertTrue($reflectionMethod->invoke($event));

        // 1日後
        $this->travelTo($latestEventDate->date->copy()->addDay());
        $this->assertFalse($reflectionMethod->invoke($event));
    }

    public function testUpdateIsProgress()
    {
        $event = Event::factory()->hasEventDates(3)->create([
            'is_progress' => false,
        ]);
        $latestEventDate = $event->eventDates()->latest('date')->first();

        $this->travelTo($latestEventDate->date);
        $event->updateIsProgress();
        $event->refresh();
        $this->assertTrue($event->is_progress);

        $this->travelTo($latestEventDate->date->copy()->addDay());
        $event->updateIsProgress();
        $event->refresh();
        $this->assertFalse($event->is_progress);
    }

    public function testScopeFilterUnderwayEvents()
    {
        $underwayEvent = Event::factory()->create([
            'is_progress' => true,
        ]);
        $finishedEvent = Event::factory()->create([
            'is_progress' => false,
        ]);

        $eventIds = Event::filterUnderwayEvents()->pluck('id');
        $this->assertContains($underwayEvent->id, $eventIds);
        $this->assertNotContains($finishedEvent->id, $eventIds);
    }

    public function testScopeFilterFinishedEvents()
    {
        $underwayEvent = Event::factory()->create([
            'is_progress' => true,
        ]);
        $finishedEvent = Event::factory()->create([
            'is_progress' => false,
        ]);

        $eventIds = Event::filterFinishedEvents()->pluck('id');
        $this->assertNotContains($underwayEvent->id, $eventIds);
        $this->assertContains($finishedEvent->id, $eventIds);
    }
}
