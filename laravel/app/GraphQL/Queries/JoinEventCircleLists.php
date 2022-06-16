<?php

namespace App\GraphQL\Queries;

use App\Models\ViewCircleList;

class JoinEventCircleLists
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        return ViewCircleList::where('join_event_id', $args['join_event_id'])
            ->orderByPlacement()
            ->get();
    }
}
