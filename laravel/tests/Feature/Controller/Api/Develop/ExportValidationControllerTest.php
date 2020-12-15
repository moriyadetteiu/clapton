<?php

namespace Tests\Feature\Api\Develop;

use Illuminate\Support\Collection;

use App\Support\Validation\ValidationExtractorInterface;
use Tests\TestCase;

class ExportValidationController extends TestCase
{
    public function testExport()
    {
        $this->app->bind(ValidationExtractorInterface::class, MockValidationExtractor::class);

        $response = $this->get('api/develop/export-validation');

        $response->assertStatus(200);

        $response->assertJson(MockValidationExtractor::RESULT);
    }
}

class MockValidationExtractor implements ValidationExtractorInterface
{
    public const RESULT = [
        [
            'test' => [],
            'rules' => ['required'],
            'attribute' => ['テスト'],
        ]
    ];

    public function extract(): Collection
    {
        return collect(self::RESULT);
    }
}
