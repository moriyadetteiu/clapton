<?php

namespace App\UseCase\CareAboutCircle;

use App\UseCase\UseCaseInput;

class CreateCareAboutCircleInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'join_event_id' => 'required',
            'circle_placement_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'join_event_id' => 'イベント参加番号',
            'circle_placement_id' => 'サークル配置番号',
        ];
    }
}
