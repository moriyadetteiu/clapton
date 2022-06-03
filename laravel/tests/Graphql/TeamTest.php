<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;

class TeamTest extends TestCase
{
    use WithFaker;

    public function testCreateTeam()
    {
        $teamInput = [
            'name' => $this->faker->name,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
                mutation createTeam($input: TeamInput!) {
                    createTeam(input: $input) {
                        id
                        name
                        code
                    }
                }
            ', [
                'input' => $teamInput
            ]);

        // 登録の確認
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createTeam' => $teamInput
                ]
            ]);
        $responseData = $response->json('data.createTeam');
        $this->assertIsUuid($responseData['id']);
        $this->assertTrue(preg_match('/^[0-9a-f]{13}$/', $responseData['code']) === 1);
        $this->assertDatabaseHas('teams', $responseData);
        $this->assertDatabaseHas('user_affiliation_teams', [
            'user_id' => $this->loginUser->id,
            'team_id' => $responseData['id'],
        ]);
    }
}
