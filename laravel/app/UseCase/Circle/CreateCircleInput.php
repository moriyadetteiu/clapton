<?php

namespace App\UseCase\Circle;

use App\UseCase\UseCaseInput;

class CreateCircleInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => ''
        ];
    }
}
