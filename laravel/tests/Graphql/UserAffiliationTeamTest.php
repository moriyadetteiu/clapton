<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Team;
use App\Models\User;

// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAffiliationTeamTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testUserJoinsTeam()
    {
        $team = Team::factory()->create();
        $user = User::factory()->create();

        $userJoinsTeamInput = [
            'team_id' => $team->id,
        ];

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
            mutation joinTeam($team_id: ID!) {
                joinTeam(team_id: $team_id) {
                    id
                    user {
                        id
                    }
                    team {
                        id
                    }
                }
            }
        ', $userJoinsTeamInput);

        $responseData = $response->json('data.joinTeam');
        $this->assertIsUuid($responseData['id']);
        $this->assertDatabaseHas('user_affiliation_teams', $userJoinsTeamInput);

        $this->assertEquals($team->id, $responseData['team']['id']);
        $this->assertEquals($user->id, $responseData['user']['id']);
    }
}
