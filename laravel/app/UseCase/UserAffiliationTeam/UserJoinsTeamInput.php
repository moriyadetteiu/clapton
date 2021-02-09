<?php

namespace App\UseCase\UserAffiliationTeam;

use App\UseCase\UseCaseInput;

class UserJoinsTeamInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'user_id' => 'required',
            'team_id' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'user_id' => 'ユーザ番号',
            'team_id' => 'チーム番号'
        ];
    }
}
