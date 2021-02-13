<?php

namespace Tests\Unit\UseCase\User;

use Tests\TestCase;
use App\UseCase\ValidationException;
use Illuminate\Support\Arr;

use App\UseCase\User\CreateUserInput;
use App\Models\User;

class CreateUserInputTest extends TestCase
{
    /**
     * @dataProvider provideRequiredKeys
     */
    public function testInvalidRequired($requiredInput): void
    {
        $validInput = $this->makeValidInput();
        $testInput = Arr::except($validInput, [$requiredInput]);

        $this->expectException(ValidationException::class);
        new CreateUserInput($testInput);
    }

    public function provideRequiredKeys(): array
    {
        return [
            ['name'],
            ['name_kana'],
            ['handle_name'],
            ['handle_name_kana'],
            ['email'],
            ['password'],
        ];
    }

    public function testInvalidMail(): void
    {
        $testInput = $this->makeValidInput();
        $testInput['email'] = 'invalid_address';

        $this->expectException(ValidationException::class);
        new CreateUserInput($testInput);
    }

    public function testValidInput(): void
    {
        $validInput = $this->makeValidInput();
        $createUserInput = new CreateUserInput($validInput);
        $this->assertIsArray($createUserInput->toArray());
    }

    protected function makeValidInput(): array
    {
        $definition = User::factory()->definition();
        // 暗号化済みの値がfactoryされるので、適当な値を入れる
        $definition['password'] = 'test';
        return Arr::only($definition, ['name', 'name_kana', 'handle_name', 'handle_name_kana', 'email', 'password']);
    }
}
