<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

use App\Models\Circle;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Team;
use App\Models\User;
use App\Models\EventAffiliationTeam;
use App\Models\UserAffiliationTeam;
use App\Models\JoinEvent;
use App\Models\JoinEventDate;

class CareAboutCircleTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateCircleParticipatingInEvent()
    {
        $event = Event::factory()->create();
        $eventDate = EventDate::factory([
            'event_id' => $event->id
        ])->create();
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $eventAffiliationTeam = EventAffiliationTeam::factory([
            'team_id' => $team->id,
            'event_id' => $event->id,
        ])->create();
        UserAffiliationTeam::factory([
            'user_id' => $user->id,
            'team_id' => $team->id
        ])->create();
        $joinEvent = JoinEvent::factory([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'event_id' => $event->id,
        ])->create();
        $joinEventDate = JoinEventDate::factory([
            'join_event_id' => $joinEvent->id,
            'event_date_id' => $eventDate->id,
        ])->create();
        $circlePlacementClassification = $team->circlePlacementClassifications()->inRandomOrder()->first();
        $circle = Circle::factory()->create();


        $createCircleParticipatingInEventInput = [
            'circle_id' => $circle->id,
            'join_event_id' => $joinEvent->id,
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                mutation createCareAboutCircle($input: CareAboutCircleInput!) {
                    createCareAboutCircle(input: $input) {
                        id
                        circle {
                            id
                        }
                        joinEvent {
                            id
                        }
                    }
                }
            ', [
                'input' => $createCircleParticipatingInEventInput
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createCareAboutCircle' => [
                        'circle' => [
                            'id' => $circle->id,
                        ],
                        'joinEvent' => [
                            'id' => $joinEvent->id,
                        ],
                    ],
                ]
            ]);

        $this->assertDatabaseHas('care_about_circles', $createCircleParticipatingInEventInput);
    }
}
