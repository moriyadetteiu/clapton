<?php

namespace App\UseCase\CircleProductClassification;

use App\UseCase\UseCaseInput;

class CreateCircleProductClassificationInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'team_id' => 'required',
            'name' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'team_id' => 'チーム番号',
            'name' => '頒布物分類名',
        ];
    }
}
