<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;

class Login
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = auth('api');
        $credentials = Arr::only($args, ['email', 'password']);
        if (!$token = $auth->attempt($credentials)) {
            return ['error' => 'Unauthorized'];
        }
        return [
            'token' => $token,
            'expires_in' => $auth->factory()->getTTL() * 60,
        ];
    }
}
