<?php

namespace App\GraphQL\Mutations;

use Auth;

use Illuminate\Support\Arr;

use App\UseCase\Team\CreateTeam as CreateTeamUseCase;
use App\UseCase\Team\CreateTeamInput;

class CreateTeam
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $userData = Arr::except($args, ['directive']);
        $userData['owner_user_id'] = Auth::id();
        $input = new CreateTeamInput($userData);
        return (new CreateTeamUseCase())->execute($input);
    }
}
