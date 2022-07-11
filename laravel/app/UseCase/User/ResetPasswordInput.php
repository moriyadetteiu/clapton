<?php

namespace App\UseCase\User;

use App\UseCase\UseCaseInput;

class ResetPasswordInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'email' => 'メールアドレス',
            'token' => 'トークン',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード確認',
        ];
    }

    public function getCredentials(): array
    {
        return collect($this->toArray())
            ->only([
                'email',
                'token',
                'password',
                'password_confirmation',
            ])->toArray();
    }
}
