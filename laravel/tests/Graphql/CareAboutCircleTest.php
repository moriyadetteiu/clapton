<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\CareAboutCircle;
use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Team;
use App\Models\User;
use App\Models\EventAffiliationTeam;
use App\Models\Favorite;
use App\Models\UserAffiliationTeam;
use App\Models\JoinEvent;
use App\Models\JoinEventDate;
use Database\DatasetFactories\CircleDatasetFactory;

class CareAboutCircleTest extends TestCase
{
    use WithFaker;

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
        $circlePlacement = CirclePlacement::factory([
            'circle_id' => $circle->id,
            'event_date_id' => $eventDate->id,
        ])->create();


        $createCircleParticipatingInEventInput = [
            'circle_placement_id' => $circlePlacement->id,
            'join_event_id' => $joinEvent->id,
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                mutation createCareAboutCircle($input: CareAboutCircleInput!) {
                    createCareAboutCircle(input: $input) {
                        id
                        circlePlacement {
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
                        'circlePlacement' => [
                            'id' => $circlePlacement->id,
                        ],
                        'joinEvent' => [
                            'id' => $joinEvent->id,
                        ],
                    ],
                ]
            ]);

        $this->assertDatabaseHas('care_about_circles', $createCircleParticipatingInEventInput);
    }

    public function testDontCareCircleWhenOnlyLoginUser()
    {
        $dataset = (new CircleDatasetFactory)->one()->create();
        $user = $dataset['user'];
        $careAboutCircle = $dataset['careAboutCircles']->first();
        $circlePlacement = $careAboutCircle->circlePlacement;
        $circle = $circlePlacement->circle;

        $this
            ->actingAsUser($user)
            ->graphQL('
                mutation dontCareCircle($id: ID!) {
                    dontCareCircle(id: $id) {
                        id
                    }
                }
            ', [
                'id' => $careAboutCircle->id
            ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('care_about_circles', ['id' => $careAboutCircle->id]);
        $this->assertDatabaseMissing('circles', ['id' => $circle->id]);
        $this->assertDatabaseMissing('circle_placements', ['id' => $circlePlacement->id]);
    }

    public function testDontCareCircleWhenExistsOtherUserCareAboutCircle()
    {
        $dataset = (new CircleDatasetFactory)->one()->create();
        $user = $dataset['user'];
        $careAboutCircle = $dataset['careAboutCircles']->first();
        $circlePlacement = $careAboutCircle->circlePlacement;
        $circle = $circlePlacement->circle;

        CareAboutCircle::factory([
            'circle_placement_id' => $circlePlacement->id,
        ])->create();

        $this
            ->actingAsUser($user)
            ->graphQL('
                mutation dontCareCircle($id: ID!) {
                    dontCareCircle(id: $id) {
                        id
                    }
                }
            ', [
                'id' => $careAboutCircle->id
            ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('care_about_circles', ['id' => $careAboutCircle->id]);
        $this->assertDatabaseHas('circles', ['id' => $circle->id]);
        $this->assertDatabaseHas('circle_placements', ['id' => $circlePlacement->id]);
    }

    public function testDontCareCircleWhenExistsFavorite()
    {
        $dataset = (new CircleDatasetFactory)->one()->create();
        $user = $dataset['user'];
        $careAboutCircle = $dataset['careAboutCircles']->first();
        $circlePlacement = $careAboutCircle->circlePlacement;
        $circle = $circlePlacement->circle;

        Favorite::factory([
            'circle_id' => $circle->id,
        ])->create();

        $this
            ->actingAsUser($user)
            ->graphQL('
                mutation dontCareCircle($id: ID!) {
                    dontCareCircle(id: $id) {
                        id
                    }
                }
            ', [
                'id' => $careAboutCircle->id
            ])
            ->assertStatus(200);

        $this->assertDatabaseMissing('care_about_circles', ['id' => $careAboutCircle->id]);
        $this->assertDatabaseHas('circles', ['id' => $circle->id]);
        $this->assertDatabaseHas('circle_placements', ['id' => $circlePlacement->id]);
    }
}
