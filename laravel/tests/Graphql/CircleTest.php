<?php

namespace Tests\Graphql;

use App\Models\CareAboutCircle;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\Event;
use App\Models\EventDate;
use App\Models\Favorite;
use App\Models\Team;
use App\Models\User;
use App\Models\EventAffiliationTeam;
use App\Models\UserAffiliationTeam;
use App\Models\JoinEvent;
use App\Models\JoinEventDate;
use App\Models\NotParticipationCircle;
use Database\DatasetFactories\CircleDatasetFactory;

class CircleTest extends TestCase
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

        $createCircleParticipatingInEventInput = [
            'circle' => [
                'name' => $this->faker->name,
                'kana' => $this->faker->name,
                'memo' => $this->faker->text,
            ],
            'placement' => [
                'event_date_id' => $eventDate->id,
                'hole' => $this->faker->randomElement(['東', '西', '南']),
                'line' => $this->faker->randomElement(range('A', 'Z')),
                'number' => $this->faker->numberBetween(0, 80),
                'a_or_b' => $this->faker->randomElement(['a', 'b']),
                'circle_placement_classification_id' => $circlePlacementClassification->id,
            ]
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                mutation createCircleParticipatingInEvent($input: CreateCircleParticipatingInEventInput!) {
                    createCircleParticipatingInEvent(input: $input) {
                        id
                        circle {
                            id
                            name
                            kana
                            memo
                        }
                    }
                }
            ', [
                'input' => $createCircleParticipatingInEventInput
            ]);

        $expectedCircleData = $createCircleParticipatingInEventInput['circle'];
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createCircleParticipatingInEvent' => [
                        'circle' => $expectedCircleData,
                    ],
                ],
            ]);
        $responseData = $response->json('data.createCircleParticipatingInEvent');

        $circle = Circle::findOrFail($responseData['circle']['id']);

        $placement = CirclePlacement::findOrFail($responseData['id']);
        $expectedPlacement = $createCircleParticipatingInEventInput['placement'];
        $expectedPlacement['circle_id'] = $circle->id;
        $assertionPlacement = Arr::except($placement->toArray(), ['id', 'created_at', 'updated_at']);
        $this->assertEquals($expectedPlacement, $assertionPlacement);
    }

    public function testUpdateCircleParticipatingInEvent()
    {
        $dataset = (new CircleDatasetFactory())
            ->one()
            ->create();
        $user = $dataset['user'];
        $team = $dataset['team'];

        $placementValues = collect(CirclePlacement::factory()->definition())
            ->except('circle_id', 'event_date_id', 'circle_placement_classification_id')
            ->put('event_date_id', $dataset['eventDates']->random()->id)
            ->put('circle_placement_classification_id', $team->circlePlacementClassifications->random()->id)
            ->toArray();

        $updateCircleParticipatingInEventInput = [
            'circle' => Circle::factory()->definition(),
            'placement' => $placementValues,
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                mutation updateCircleParticipatingInEvent($id: ID!, $input: CreateCircleParticipatingInEventInput!) {
                    updateCircleParticipatingInEvent(id: $id, input: $input) {
                        id
                        circle {
                            id
                            name
                            kana
                            memo
                        }
                    }
                }
            ', [
                'id' => $dataset['circle']->id,
                'input' => $updateCircleParticipatingInEventInput
            ]);
        $responseData = $response->json('data.updateCircleParticipatingInEvent');

        $circle = Circle::findOrFail($responseData['circle']['id']);

        $placement = CirclePlacement::findOrFail($responseData['id']);
        $expectedPlacement = $updateCircleParticipatingInEventInput['placement'];
        $expectedPlacement['circle_id'] = $circle->id;
        $assertionPlacement = Arr::except($placement->toArray(), ['id', 'created_at', 'updated_at']);
        $this->assertEquals($expectedPlacement, $assertionPlacement);
    }

    public function testUpdateCircleParticipatingInEventWhenHasOtherUserCareAboutCircle()
    {
        $dataset = (new CircleDatasetFactory())
            ->one()
            ->create();
        $user = $dataset['user'];
        $team = $dataset['team'];
        CareAboutCircle::factory([
            'circle_placement_id' => $dataset['circlePlacements']->first()->id,
        ])->create();

        $placementValues = collect(CirclePlacement::factory()->definition())
            ->except('circle_id', 'event_date_id', 'circle_placement_classification_id')
            ->put('event_date_id', $dataset['eventDates']->random()->id)
            ->put('circle_placement_classification_id', $team->circlePlacementClassifications->random()->id)
            ->toArray();

        $updateCircleParticipatingInEventInput = [
            'circle' => Circle::factory()->definition(),
            'placement' => $placementValues,
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                mutation updateCircleParticipatingInEvent($id: ID!, $input: CreateCircleParticipatingInEventInput!) {
                    updateCircleParticipatingInEvent(id: $id, input: $input) {
                        id
                        circle {
                            id
                            name
                            kana
                            memo
                        }
                    }
                }
            ', [
                'id' => $dataset['circle']->id,
                'input' => $updateCircleParticipatingInEventInput
            ]);

        $errors = $response->json('errors');
        $this->assertEquals($errors[0]['extensions']['category'], 'updateDenied');
    }

    public function testUpdateCircleParticipatingInEventWhenHasOtherUserFavorite()
    {
        $dataset = (new CircleDatasetFactory())
            ->one()
            ->create();
        $user = $dataset['user'];
        $team = $dataset['team'];
        Favorite::factory([
            'circle_id' => $dataset['circle']->id,
        ])->create();

        $placementValues = collect(CirclePlacement::factory()->definition())
            ->except('circle_id', 'event_date_id', 'circle_placement_classification_id')
            ->put('event_date_id', $dataset['eventDates']->random()->id)
            ->put('circle_placement_classification_id', $team->circlePlacementClassifications->random()->id)
            ->toArray();

        $updateCircleParticipatingInEventInput = [
            'circle' => Circle::factory()->definition(),
            'placement' => $placementValues,
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                mutation updateCircleParticipatingInEvent($id: ID!, $input: CreateCircleParticipatingInEventInput!) {
                    updateCircleParticipatingInEvent(id: $id, input: $input) {
                        id
                        circle {
                            id
                            name
                            kana
                            memo
                        }
                    }
                }
            ', [
                'id' => $dataset['circle']->id,
                'input' => $updateCircleParticipatingInEventInput
            ]);

        $errors = $response->json('errors');
        $this->assertEquals($errors[0]['extensions']['category'], 'updateDenied');
    }

    public function testNotParticipateCircleInEvent()
    {
        $dataset = (new CircleDatasetFactory())
            ->one()
            ->create();
        $circle = $dataset['circle'];
        $event = $dataset['event'];
        $input = [
            'circleId' => $circle->id,
            'eventId' => $event->id,
        ];

        $response = $this
            ->actingAsUser($dataset['user'])
            ->graphQL('
                mutation notParticipateCircleInEvent($circleId: ID!, $eventId: ID!) {
                    notParticipateCircleInEvent(circle_id: $circleId, event_id: $eventId) {
                        id
                    }
                }
            ', $input)
            ->assertStatus(200);

        $this->assertDatabaseHas('not_participation_circles', [
            'circle_id' => $circle->id,
            'event_id' => $event->id,
        ]);
    }

    public function testCancelNotParticipateCircleInEvent()
    {
        $dataset = (new CircleDatasetFactory())
            ->one()
            ->create();
        $circle = $dataset['circle'];
        $event = $dataset['event'];
        $input = [
            'circleId' => $circle->id,
            'eventId' => $event->id,
        ];

        NotParticipationCircle::factory([
            'circle_id' => $circle->id,
            'event_id' => $event->id,
        ])->create();

        $response = $this
            ->actingAsUser($dataset['user'])
            ->graphQL('
                mutation cancelNotParticipateCircleInEvent($circleId: ID!, $eventId: ID!) {
                    cancelNotParticipateCircleInEvent(circle_id: $circleId, event_id: $eventId) {
                        id
                    }
                }
            ', $input)
            ->assertStatus(200);

        $this->assertDatabaseMissing('not_participation_circles', [
            'circle_id' => $circle->id,
            'event_id' => $event->id,
        ]);
    }
}
