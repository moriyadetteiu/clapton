<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserTest extends TestCase
{
    use WithFaker;

    public function testCreateUser()
    {
        $userInput = [
            'name' => $this->faker->name,
            'name_kana' => $this->faker->name,
            'handle_name' => $this->faker->name,
            'handle_name_kana' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
        ];

        $response = $this->graphQL('
            mutation createUser($input: UserInput!) {
                createUser(input: $input) {
                    id
                    name
                    name_kana
                    handle_name
                    handle_name_kana
                    email
                }
            }
        ', [
            'input' => $userInput
        ]);

        // 登録の確認
        $expectResponseData = Arr::only($userInput, ['name', 'email']);
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'createUser' => $expectResponseData
                ]
            ]);
        $responseData = $response->json('data.createUser');
        $this->assertIsUuid($responseData['id']);
        $this->assertDatabaseHas('users', $responseData);

        // 認証情報の確認
        $credentials = Arr::only($userInput, ['email', 'password']);
        $this->assertTrue(Auth::attempt($credentials));
    }
}
