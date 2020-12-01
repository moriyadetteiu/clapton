<?php

namespace App\UseCase\User;

use App\UseCase\UseCase;
use App\UseCase\User\CreateUserInput;
use App\Models\User;

class CreateUser extends UseCase
{
    public function execute(CreateUserInput $input)
    {
        $userData = $input->toArray();
        $userData['password'] = User::encryptPassword($userData['password']);
        $user = User::create($userData);
        return $user;
    }
}
