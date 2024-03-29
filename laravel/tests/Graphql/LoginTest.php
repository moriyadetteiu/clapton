<?php

namespace Tests\Graphql;

use Illuminate\Foundation\Testing\WithFaker;

use App\Models\User;

class LoginTest extends TestCase
{
    use WithFaker;

    private const LOGIN_MUTATION = '
        mutation login($input: LoginInput!) {
            login(input: $input) {
                error
            }
        }
    ';

    private $credentials = null;

    public function setUp(): void
    {
        parent::setUp();

        $credentials = [
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
        ];
        $this->credentials = $credentials;

        $encryptedCredentials = $credentials;
        $encryptedCredentials['password'] = User::encryptPassword($credentials['password']);

        $this->user = User::factory($encryptedCredentials)->create();
    }

    public function testLoginSuccess()
    {
        $response = $this->graphQL(self::LOGIN_MUTATION, [
            'input' => $this->credentials
        ]);

        // 登録の確認
        $response
            ->assertStatus(200);
        $responseData = $response->json('data.login');

        $this->assertNull($responseData['error']);
    }

    public function testLoginFail()
    {
        $credentials = $this->credentials;
        $credentials['password'] .= 'dummy';

        $response = $this->graphQL(self::LOGIN_MUTATION, [
            'input' => $credentials
        ]);

        $response
            ->assertStatus(200);
        $responseData = $response->json('data.login');

        $this->assertIsString($responseData['error']);
    }
}
