<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
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
        ];

        $response = $this->graphQL('
            mutation createEvent($input: EventInput!) {
                createEvent(input: $input) {
                    id
                    name
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
        $this->assertDatabaseHas('events', $responseData);
    }
}
