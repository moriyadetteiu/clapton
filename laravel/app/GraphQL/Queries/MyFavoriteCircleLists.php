<?php

namespace App\GraphQL\Queries;

use Auth;

class MyFavoriteCircleLists
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $eventId = $args['event_id'];
        $user = Auth::user();
        $user->load('favorites.circle');
        return $user->favorites->map(fn ($favorite) => [
            'favorite' => $favorite,
            'state' => $favorite->participateInEventState($eventId),
        ]);
    }
}
