<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use Illuminate\Support\Arr;

class CreateTeam
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO implement the resolver
        $teamData = Arr::except($args, ['directive']);
        $code = Team::generateCode();
        $teamData['code'] = $code;
        $team = Team::create($teamData);
        return $team;
    }
}
