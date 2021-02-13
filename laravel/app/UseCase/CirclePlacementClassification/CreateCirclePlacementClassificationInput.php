<?php

namespace App\UseCase\CirclePlacementClassification;

use App\UseCase\UseCaseInput;

class CreateCirclePlacementClassificationInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'team_id' => 'required',
            'name' => 'required',
            'cost' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'team_id' => 'チーム番号',
            'name' => '配置分類名',
            'cost' => 'コスト'
        ];
    }
}
