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
            'join_event_id' => 'required',
            'circle_product_id' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'quantity' => '個数',
            'want_priority_id' => '優先度',
            'join_event_id' => 'イベント参加番号',
            'circle_product_id' => '頒布物番号',
        ];
    }
}
