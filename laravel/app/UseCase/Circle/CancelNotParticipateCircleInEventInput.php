<?php

namespace App\UseCase\Circle;

use App\UseCase\UseCaseInput;

class CancelNotParticipateCircleInEventInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'circle_id' => 'required',
            'event_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'circle_id' => 'サークル番号',
            'event_id' => 'イベント番号',
        ];
    }
}
