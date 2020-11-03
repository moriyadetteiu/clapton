<?php

namespace Tests\Graphql;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MakesGraphQLRequests;
}
