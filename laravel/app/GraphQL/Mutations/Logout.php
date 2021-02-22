<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;

class Logout
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = auth('api');
        $auth->logout();
        return [];
    }
}
