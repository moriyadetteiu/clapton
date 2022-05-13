<?php

namespace App\GraphQL\Mutations;

use Auth;

use Illuminate\Support\Arr;

class Login
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $credentials = Arr::only($args, ['email', 'password']);
        $rememberMe = $args['remember_me'] ?? false;
        if (!Auth::attempt($credentials, $rememberMe)) {
            return ['error' => 'Unauthorized'];
        }

        session()->regenerate();
        return [
            'error' => null,
        ];
    }
}
