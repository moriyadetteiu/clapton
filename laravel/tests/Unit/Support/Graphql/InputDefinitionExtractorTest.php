<?php

namespace Tests\Unit\Support\Graphql;

use Tests\TestCase;

use App\Support\Graphql\InputDefinitionExtractor;

class InputDefinitionExtractorTest extends TestCase
{
    public function testExtract()
    {
        // TODO: スキーマに依存しないモックを利用したテストにしたい
        $extractor = $this->app->make(InputDefinitionExtractor::class);
        $definitions = $extractor->extract('UserInput');
        $nameDefinition = $definitions->first();
        $this->assertEquals('name', $nameDefinition->getName());
        $this->assertEquals('String', $nameDefinition->getType());
        $this->assertEquals(false, $nameDefinition->getCanNull());
    }
}
