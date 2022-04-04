<?php

namespace App\UseCase\WantCircleProduct;

use App\UseCase\UseCaseInput;

class CreateWantCircleProductInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'quantity' => 'required',
            'want_priority_id' => 'required',
            'user_id' => 'required',
            'circle_product_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'quantity' => '個数',
            'want_priority_id' => '優先度',
            'user_id' => 'ユーザ番号',
            'circle_product_id' => '頒布物番号',
        ];
    }
}
