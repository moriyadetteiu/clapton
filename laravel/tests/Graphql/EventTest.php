<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateEvent()
    {
        $eventInput = [
            'name' => $this->faker->name,
            'event_dates' => [[
                'name' => $this->faker->name,
                'date' => $this->faker->date($format='Y-m-d', $max='now'),
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
                    event_dates {
                        id
                        event_id
                        name
                        date
                        is_production_day
                      }
                }
            }
        ', [
            'input' => $eventInput
        ]);
        // 登録の確認
        $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'createEvent' => $eventInput
                ]
                ]);
                $responseData = $response->json()['data']['createEvent'];
                $this->assertIsUuid($responseData['id']);
                $this->assertDatabaseHas('events', Arr::except($responseData, ['event_dates']));
                
        foreach ($responseData['event_dates'] as $eventDate) {
            $this->assertIsUuid($eventDate['id']);
            $this->assertDatabaseHas('event_dates', $eventDate);
        }
    }
}
