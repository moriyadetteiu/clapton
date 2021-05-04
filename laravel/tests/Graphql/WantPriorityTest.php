<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Team;
use App\Models\WantPriority;

class WantPriorityTest extends TestCase
{
    use WithFaker;

    public function testCreateWantPriority()
    {
        $team = Team::factory()->create();

        $wantPriorityInput = [
            'name' => $this->faker->name,
            'team_id' => $team->id,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation createWantPriority($input: WantPriorityInput!) {
                createWantPriority(input: $input) {
                    id
                    name
                    team {
                        id
                    }
                }
            }
        ', [
                'input' => $wantPriorityInput
            ]);

        $expectResponseData = Arr::only($wantPriorityInput, ['name']);

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createWantPriority' => $expectResponseData
                ]
            ]);
        $responseData = $response->json('data.createWantPriority');
        $this->assertIsUuid($responseData['id']);
        $this->assertDatabaseHas('want_priorities', $wantPriorityInput);

        $this->assertEquals($team->id, $responseData['team']['id']);
    }

    public function testUpdateWantPriority()
    {
        $wantPriority = WantPriority::factory()->create();

        $wantPriorityInput = [
            'name' => $this->faker->name,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation updateWantPriority($id: ID!, $input: WantPriorityInput!) {
                updateWantPriority(id: $id, input: $input) {
                    id
                    name
                }
            }
        ', [
                'id' => $wantPriority->id,
                'input' => $wantPriorityInput
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'updateWantPriority' => $wantPriorityInput
                ]
            ]);

        $wantPriority->refresh();
        $this->assertEquals($wantPriorityInput['name'], $wantPriority->name);
    }

    public function testDeleteWantPriority()
    {
        $wantPriority = WantPriority::factory()->create();

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation deleteWantPriority($id: ID!) {
                deleteWantPriority(id: $id) {
                    id
                }
            }
        ', [
                'id' => $wantPriority->id
            ]);

        $response
            ->assertStatus(200);

        $model = WantPriority::find($wantPriority->id);
        $this->assertNull($model);
    }

    public function testWantPrioritys()
    {
        $team = Team::factory()->create();
        $team->wantPriorities->map(fn ($model) => $model->delete());

        $wantPriorities = WantPriority::factory()
            ->count(3)
            ->create(['team_id' => $team->id]);

        $response = $this
            ->actingAsUser()
            ->graphQL('
            query wantPriorities($teamId: ID!) {
                wantPriorities(team_id: $teamId) {
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

        $responseData = $response->json('data.wantPriorities');
        $responseIds = array_column($responseData, 'id');
        $expectedIds = $wantPriorities->pluck('id')->all();
        $this->assertEquals($responseIds, $expectedIds);
        $this->assertCount(3, $responseIds);
    }
}
