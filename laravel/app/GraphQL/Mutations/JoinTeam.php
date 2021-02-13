<?php

namespace App\GraphQL\Mutations;

use App\UseCase\UserAffiliationTeam\JoinTeam as JoinTeamUseCase;
use App\UseCase\UserAffiliationTeam\JoinTeamInput;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class JoinTeam
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $data['user_id'] = Auth::id();
        $input = new JoinTeamInput($data);
        return (new JoinTeamUseCase())->execute($input);
    }
}
