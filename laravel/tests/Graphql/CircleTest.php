<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
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
                        name
                        kana
                        memo
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
                    'createCircleParticipatingInEvent' => $expectedCircleData,
                ]
            ]);
        $responseData = $response->json('data.createCircleParticipatingInEvent');

        $circle = Circle::findOrFail($responseData['id']);

        $placement = $circle->circlePlacements()->first();
        $expectedPlacement = $createCircleParticipatingInEventInput['placement'];
        $expectedPlacement['circle_id'] = $circle->id;
        $assertionPlacement = Arr::except($placement->toArray(), ['id', 'created_at', 'updated_at']);
        $this->assertEquals($expectedPlacement, $assertionPlacement);
    }

    public function testUpdateCircleParticipatingInEvent()
    {
        $this->markTestSkipped('Factoryを整備したら追加する');
    }
}
