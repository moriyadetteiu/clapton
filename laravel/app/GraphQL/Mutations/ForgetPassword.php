<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Arr;

use App\UseCase\User\ForgetPassword as ForgetPasswordUseCase;
use App\UseCase\User\ForgetPasswordInput;

class ForgetPassword
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = (new ForgetPasswordInput($data));
        $errorMessage = (new ForgetPasswordUseCase())->execute($input);

        if (!$errorMessage) {
            return [];
        }

        return [
            'error' => $errorMessage
        ];
    }
}
