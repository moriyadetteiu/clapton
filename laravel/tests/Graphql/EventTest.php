<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Team;

// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateEvent()
    {
        $team = Team::factory()->create();

        $eventInput = [
            'name' => $this->faker->name,
            'team_id' => $team->id,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation createEvent($input: EventInput!) {
                createEvent(input: $input) {
                    id
                    name
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
        $this->assertDatabaseHas('events', $responseData);

        $expectedAffiliation = [
            'event_id' => $responseData['id'],
            'team_id' => $eventInput['team_id']
        ];
        $this->assertDatabaseHas('event_affiliation_teams', $expectedAffiliation);
    }
}
