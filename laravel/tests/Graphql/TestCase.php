<?php

namespace Tests\Graphql;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase as BaseTestCase;

use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequests;
    use RefreshDatabase;

    protected $dropViews = true;
    protected $loginUser = null;

    protected function assertIsUuid($value): void
    {
        $isUuid = preg_match('/[\d\-a-f]{36}/', $value) === 1;
        $this->assertTrue($isUuid);
    }

    protected function actingAsUser(User $user = null)
    {
        if (!$user) {
            $user = User::factory()->create();
        }
        $this->loginUser = $user;

        Sanctum::actingAs($user, ['*']);

        return $this;
    }
}
