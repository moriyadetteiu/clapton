<?php

namespace App\GraphQL\Mutations;

class Login
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        if (!$token = auth('api')->attempt(['email' => $args['email'], 'password' => $args['password']])) {
            return ['error' => 'Unauthorized'];
        }
        return ['token' => $token];
    }
}
