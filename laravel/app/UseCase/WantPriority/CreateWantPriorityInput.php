<?php

namespace App\UseCase\WantPriority;

use App\UseCase\UseCaseInput;

class CreateWantPriorityInput extends UseCaseInput
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
            'name' => '優先度名',
        ];
    }
}
