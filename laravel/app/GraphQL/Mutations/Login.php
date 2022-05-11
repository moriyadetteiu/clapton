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
        if (!\Auth::attempt($credentials)) {
            return ['error' => 'Unauthorized'];
        }

        session()->regenerate();
        return [
            'error' => null,
        ];
    }
}
