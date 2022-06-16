<?php

namespace App\GraphQL\Queries;

use App\Models\ViewCircleList;

class TeamCircleLists
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return ViewCircleList::where('team_id', $args['team_id'])
            ->where('event_id', $args['event_id'])
            ->orderByPlacement()
            ->get();
    }
}
