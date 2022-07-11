<?php

namespace App\UseCase\Team;

use App\UseCase\UseCaseInput;

class CreateTeamInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'name' => 'required',
            'owner_user_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'チーム名',
            'owner_user_id' => 'チーム作成者番号',
        ];
    }
}
