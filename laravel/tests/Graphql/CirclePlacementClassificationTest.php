<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Team;

// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;

class CirclePlacementClassification extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateCirclePlacementClassification()
    {
        $team = Team::factory()->create();

        $circlePlacementClassificationInput = [
            'name' => $this->faker->name,
            'team_id' => $team->id,
            'cost' => $this->faker->numberBetween(0, 10)
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation createCirclePlacementClassification($input: CirclePlacementClassificationInput!) {
                createCirclePlacementClassification(input: $input) {
                    id
                    name
                    cost
                    team {
                        id
                    }
                }
            }
        ', [
                'input' => $circlePlacementClassificationInput
            ]);

        $expectResponseData = Arr::only($circlePlacementClassificationInput, ['name', 'cost']);

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createCirclePlacementClassification' => $expectResponseData
                ]
            ]);
        $responseData = $response->json('data.createCirclePlacementClassification');
        $this->assertIsUuid($responseData['id']);
        $this->assertDatabaseHas('circle_placement_classifications', $circlePlacementClassificationInput);

        $this->assertEquals($team->id, $responseData['team']['id']);
    }
}
