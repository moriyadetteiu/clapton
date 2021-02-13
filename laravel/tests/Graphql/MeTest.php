<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
// TODO: テスト用のDBを用意したら有効化する
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

use App\Models\Team;
use App\Models\User;

class MeTest extends TestCase
{
    use WithFaker;
    // TODO: テスト用のDBを用意したら有効化する
    // use RefreshDatabase;

    public function testCreateUser()
    {
        $user = User::factory()->create();
        $team = Team::factory()->create();
        $user->affiliateTeams()->create([
            'team_id' => $team->id
        ]);

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
            query {
                me {
                    id
                    name
                    name_kana
                    handle_name
                    handle_name_kana
                    email
                    affiliateTeams {
                        team {
                            id
                            name
                        }
                    }
                }
            }
        ');

        $data = $response->json('data.me');
        $expectUser = Arr::only($user->toArray(), array_keys($data));
        $assertionData = Arr::except($data, ['affiliateTeams']);
        $this->assertEquals($expectUser, $assertionData);

        $expectedTeam = Arr::only($team->toArray(), ['id', 'name']);
        $this->assertEquals($expectedTeam, $data['affiliateTeams'][0]['team']);
    }
}
