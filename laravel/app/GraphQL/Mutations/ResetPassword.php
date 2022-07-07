<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;

use App\UseCase\User\ResetPassword as ResetPasswordUseCase;
use App\UseCase\User\ResetPasswordInput;

class ResetPassword
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = (new ResetPasswordInput($data));
        $errorMessage = (new ResetPasswordUseCase())->execute($input);

        if (!$errorMessage) {
            return [];
        }

        return [
            'error' => $errorMessage
        ];
    }
}
