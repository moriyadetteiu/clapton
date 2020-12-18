<?php

namespace Tests\Unit\Support\Validation;

use Tests\TestCase;

use App\Support\Validation\UseCaseInputValidationExtractor;

class UseCaseInputValidationExtractorTest extends TestCase
{
    public function testExtract()
    {
        // TODO: UseCaseに依存しないモックを利用したテストにしたい
        // とりあえず代表値でCreateUserUseCaseを使ってテストする
        $extractor = $this->app->make(UseCaseInputValidationExtractor::class);
        $validations = $extractor->extract();
        $this->assertTrue($validations->has('CreateUserInput'));

        $userInputValidation = collect($validations['CreateUserInput']);

        $this->assertTrue($userInputValidation->has('name'));
        $nameValidation = collect($userInputValidation['name']);

        $this->assertTrue($nameValidation->has('rules'));
        $this->assertTrue($nameValidation->has('attribute'));

        $this->assertEquals('required', $nameValidation['rules']);
        $this->assertEquals('名前', $nameValidation['attribute']);
    }
}
