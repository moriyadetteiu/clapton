<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;

use App\Models\Team;
use App\Models\User;
use App\Models\WantPriority;

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
            'user_id' => $user->id,
        ];

        $response = $this
            ->actingAsUser()
            ->graphQL('
            mutation userJoinsTeam($user_id: ID!, $team_id: ID!) {
                userJoinsTeam(user_id: $user_id, team_id: $team_id) {
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

        $responseData = $response->json('data.userJoinsTeam');
        $this->assertIsUuid($responseData['id']);
        $this->assertDatabaseHas('user_affiliation_teams', $userJoinsTeamInput);

        $this->assertEquals($team->id, $responseData['team']['id']);
        $this->assertEquals($user->id, $responseData['user']['id']);
    }
}
