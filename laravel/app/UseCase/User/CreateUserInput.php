<?php

namespace App\UseCase\User;

use App\UseCase\UseCaseInput;

class CreateUserInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'name' => 'required',
            'name_kana' => 'required',
            'handle_name' => 'required',
            'handle_name_kana' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => '名前',
            'name_kana' => '名前（かな）',
            'handle_name' => 'ハンドルネーム',
            'handle_name_kana' => 'ハンドルネーム（かな）',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ];
    }
}
