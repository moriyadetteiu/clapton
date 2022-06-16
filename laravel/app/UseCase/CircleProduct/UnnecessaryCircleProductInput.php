<?php

namespace App\UseCase\CircleProduct;

use App\UseCase\UseCaseInput;

class UnnecessaryCircleProductInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'id' => 'required',
            'operation_user_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'id' => '頒布物番号',
            'operation_user_id' => '操作しているユーザ番号',
        ];
    }
}
