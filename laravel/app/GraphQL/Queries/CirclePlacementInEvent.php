<?php

namespace App\GraphQL\Queries;

use App\Models\CirclePlacement;

class CirclePlacementInEvent
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $eventId = $args['event_id'];
        $circleId = $args['circle_id'];

        return CirclePlacement::inEvent($eventId)
            ->where('circle_id', $circleId)
            ->firstOrFail();
    }
}
