<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Circle;
use App\Models\Favorite;
use App\Models\User;

class FavoriteTest extends TestCase
{
    use WithFaker;

    public function testCreateFavorite()
    {
        $createFavoriteInput = Favorite::factory()->make()->toArray();

        $response = $this
            ->actingAsUser()
            ->graphQL('
                mutation createFavorite($input: FavoriteInput!) {
                    createFavorite(input: $input) {
                        id
                        user_id
                        circle_id
                        memo
                        user {
                            id
                        }
                        circle {
                            id
                        }
                    }
                }
            ', [
                'input' => $createFavoriteInput
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createFavorite' => $createFavoriteInput,
                ]
            ]);
        $responseData = $response->json('data.createFavorite');

        $this->assertDatabaseHas('favorites', $createFavoriteInput);
        $this->assertEquals($createFavoriteInput['user_id'], $responseData['user']['id']);
        $this->assertEquals($createFavoriteInput['circle_id'], $responseData['circle']['id']);
    }

    public function testMyFavorites()
    {
        $users = User::factory()
            ->hasFavorites(3)
            ->count(3)
            ->create();

        $user = $users->random();

        $response = $this
            ->actingAsUser($user)
            ->graphQL('
                query myFavorites {
                    myFavorites {
                        id
                        user_id
                        circle_id
                        memo
                    }
                }
            ');

        $response->assertStatus(200);
        $responseData = $response->json('data.myFavorites');

        $this->assertCount(3, $responseData);
        $expectedFavoriteIds = $user->favorites()->pluck('id');
        $this->assertEquals($expectedFavoriteIds, collect($responseData)->pluck('id'));
    }
}
