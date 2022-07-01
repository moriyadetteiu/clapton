<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\Event;
use App\Models\NotParticipationCircle;
use Tests\TestCase;

class CircleTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testParticipateInEventState()
    {
        $circle = Circle::factory()->create();
        $event = Event::factory()->create();
        $this->assertEquals($circle->participateInEventState($event->id), '未確認');

        $notParticipationCircle = NotParticipationCircle::factory([
            'circle_id' => $circle->id,
        ])->create();
        $circle->refresh();
        $this->assertEquals($circle->participateInEventState($notParticipationCircle->event_id), '不参加');
        $notParticipationCircle->delete();

        $circlePlacement = CirclePlacement::factory([
            'circle_id' => $circle->id,
        ])->create();
        $eventId = $circlePlacement->eventDate->event_id;
        $this->assertEquals($circle->participateInEventState($eventId), '参加');

        $this->expectException(ModelNotFoundException::class);
        $circle->participateInEventState('dummy');
    }
}
