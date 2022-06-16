<?php

namespace App\UseCase\CareAboutCircle;

use App\UseCase\UseCaseInput;

class DontCareCircleInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'id' => '気になってる番号',
        ];
    }
}
