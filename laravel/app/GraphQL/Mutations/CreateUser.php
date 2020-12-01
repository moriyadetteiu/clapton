<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;
use App\UseCase\User\CreateUser as CreateUserUseCase;
use App\UseCase\User\CreateUserInput;

class CreateUser
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $userData = Arr::except($args, ['directive']);
        $input = new CreateUserInput($userData);
        return (new CreateUserUseCase())->execute($input);
    }
}
