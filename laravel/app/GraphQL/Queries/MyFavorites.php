<?php

namespace App\GraphQL\Queries;

use Auth;

class MyFavorites
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $user = Auth::user();
        return $user->favorites;
    }
}
