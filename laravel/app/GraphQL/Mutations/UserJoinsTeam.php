<?php

namespace App\GraphQL\Mutations;

use App\UseCase\UserAffiliationTeam\UserJoinsTeam as UserJoinsTeamUseCase;
use App\UseCase\UserAffiliationTeam\UserJoinsTeamInput;
use Illuminate\Support\Arr;

class UserJoinsTeam
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new UserJoinsTeamInput($data);
        return (new UserJoinsTeamUseCase())->execute($input);
    }
}
