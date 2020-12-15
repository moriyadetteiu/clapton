<?php

namespace App\UseCase;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

/**
 * このクラスの役割は各所からUseCaseを実行する際に、渡す引数の一意性を保つこと
 * また、渡される引数のバリデーションを行い、後段では妥当性が担保されているようすること
 */
abstract class UseCaseInput
{
    private array $input = [];

    public function __construct(array $input)
    {
        $validator = $this->makeValidator($input);

        $validator->validate();

        $this->input = $input;
    }

    public function toArray(): array
    {
        return $this->input;
    }

    protected function makeValidator(array $input): Validator
    {
        return ValidatorFacade::make($input, $this->rules(), [], $this->attributes());
    }

    protected function attributes(): array
    {
        return [];
    }

    abstract protected function rules(): array;
}
