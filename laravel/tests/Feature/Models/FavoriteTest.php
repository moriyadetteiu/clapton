<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\CareAboutCircle;
use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\Event;
use App\Models\Favorite;
use App\Models\JoinEvent;
use App\Models\NotParticipationCircle;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function testParticipateInEventState()
    {
        $circle = Circle::factory()->create();
        $favorite = Favorite::factory([
            'circle_id' => $circle->id,
        ])->create();
        $event = Event::factory()->create();
        $this->assertEquals($favorite->participateInEventState($event->id), '未確認');

        $notParticipationCircle = NotParticipationCircle::factory([
            'circle_id' => $circle->id,
        ])->create();
        $circle->refresh();
        $this->assertEquals($favorite->participateInEventState($notParticipationCircle->event_id), '不参加');
        $notParticipationCircle->delete();

        $circlePlacement = CirclePlacement::factory([
            'circle_id' => $circle->id,
        ])->create();
        $eventId = $circlePlacement->eventDate->event_id;
        $this->assertEquals($favorite->participateInEventState($eventId), '参加');

        $joinEvent = JoinEvent::factory([
            'user_id' => $favorite->user_id,
            'event_id' => $eventId,
        ])->create();
        CareAboutCircle::factory([
            'join_event_id' => $joinEvent->id,
            'circle_placement_id' => $circlePlacement->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $favorite->participateInEventState('dummy');
    }
}
