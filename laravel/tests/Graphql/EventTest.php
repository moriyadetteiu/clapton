<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Team;

class EventTest extends TestCase
{
    use WithFaker;

    public function testCreateEvent()
    {
        $team = Team::factory()->create();

        $eventInput = [
            'name' => $this->faker->name,
            'team_id' => $team->id,
            'event_dates' => [[
                'name' => $this->faker->name,
                'date' => $this->faker->date('Y-m-d', 'now'),
                'is_production_day' => $this->faker->boolean,
            ]]
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation createEvent($input: EventInput!) {
                createEvent(input: $input) {
                    id
                    name
                    eventDates {
                        id
                        event {
                            id
                        }
                        name
                        date
                        is_production_day
                      }
                }
            }
        ', [
                'input' => $eventInput
            ]);

        $expectData = Arr::only($eventInput, ['name']);

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createEvent' => $expectData
                ]
            ]);
        $responseData = $response->json('data.createEvent');

        $this->assertIsUuid($responseData['id']);

        $expectedEventData = Arr::except($responseData, ['eventDates']);
        $this->assertDatabaseHas('events', $expectedEventData);

        $expectedAffiliation = [
            'event_id' => $responseData['id'],
            'team_id' => $eventInput['team_id']
        ];

        $this->assertDatabaseHas('event_affiliation_teams', $expectedAffiliation);

        foreach ($responseData['eventDates'] as $eventDate) {
            $this->assertIsUuid($eventDate['id']);
            $eventId = $eventDate['event']['id'];
            $expectedEventDate = Arr::except($eventDate, ['event']);
            $expectedEventDate['event_id'] = $eventId;
            $this->assertDatabaseHas('event_dates', $expectedEventDate);
        }
    }
}
