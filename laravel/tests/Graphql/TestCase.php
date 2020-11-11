<?php

namespace Tests\Graphql;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequests;

    protected function assertIsUuid($value): void
    {
        $isUuid = preg_match('/[\d\-a-f]{36}/', $value) === 1;
        $this->assertTrue($isUuid);
    }
}
