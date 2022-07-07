<?php

namespace App\UseCase\User;

use App\UseCase\UseCaseInput;

class ForgetPasswordInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    protected function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
        ];
    }
}
