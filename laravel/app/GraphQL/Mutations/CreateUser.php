<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class CreateUser
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO: バリデーションの実装

        $userData = Arr::except($args, ['directive']);
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);
        return $user;
    }
}
