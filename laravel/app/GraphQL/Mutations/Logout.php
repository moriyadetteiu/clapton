<?php

namespace App\GraphQL\Mutations;

class Logout
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        auth('web')->logout();
        return [];
    }
}
