<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Team;
use App\Models\CircleProductClassification;

// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;

class CircleProductClassificationTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateCircleProductClassification()
    {
        $team = Team::factory()->create();

        $circleProductClassificationInput = [
            'name' => $this->faker->name,
            'team_id' => $team->id,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation createCircleProductClassification($input: CircleProductClassificationInput!) {
                createCircleProductClassification(input: $input) {
                    id
                    name
                    team {
                        id
                    }
                }
            }
        ', [
                'input' => $circleProductClassificationInput
            ]);

        $expectResponseData = Arr::only($circleProductClassificationInput, ['name']);

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createCircleProductClassification' => $expectResponseData
                ]
            ]);
        $responseData = $response->json('data.createCircleProductClassification');
        $this->assertIsUuid($responseData['id']);
        $this->assertDatabaseHas('circle_product_classifications', $circleProductClassificationInput);

        $this->assertEquals($team->id, $responseData['team']['id']);
    }

    public function testUpdateCircleProductClassification()
    {
        $circleProductClassification = CircleProductClassification::factory()->create();

        $circleProductClassificationInput = [
            'name' => $this->faker->name,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation updateCircleProductClassification($id: ID!, $input: CircleProductClassificationInput!) {
                updateCircleProductClassification(id: $id, input: $input) {
                    id
                    name
                }
            }
        ', [
                'id' => $circleProductClassification->id,
                'input' => $circleProductClassificationInput
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'updateCircleProductClassification' => $circleProductClassificationInput
                ]
            ]);

        $circleProductClassification->refresh();
        $this->assertEquals($circleProductClassificationInput['name'], $circleProductClassification->name);
    }

    public function testDeleteCircleProductClassification()
    {
        $circleProductClassification = CircleProductClassification::factory()->create();

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation deleteCircleProductClassification($id: ID!) {
                deleteCircleProductClassification(id: $id) {
                    id
                }
            }
        ', [
                'id' => $circleProductClassification->id
            ]);

        $response
            ->assertStatus(200);

        $model = CircleProductClassification::find($circleProductClassification->id);
        $this->assertNull($model);
    }

    public function testCircleProductClassifications()
    {
        $team = Team::factory()->create();
        $team->circleProductClassifications->map(fn ($model) => $model->delete());

        $circleProductClassifications = CircleProductClassification::factory()
            ->count(3)
            ->create(['team_id' => $team->id]);

        $response = $this
            ->actingAsUser()
            ->graphQL('
            query circleProductClassifications($teamId: ID!) {
                circleProductClassifications(team_id: $teamId) {
                    id
                    name
                    team {
                        id
                    }
                }
            }
        ', [
                'teamId' => $team->id
            ]);

        $response
            ->assertStatus(200);

        $responseData = $response->json('data.circleProductClassifications');
        $responseIds = array_column($responseData, 'id');
        $expectedIds = $circleProductClassifications->pluck('id')->all();
        $this->assertEquals($responseIds, $expectedIds);
        $this->assertCount(3, $responseIds);
    }
}
