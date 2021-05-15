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
            'care_about_circle_id' => 'required',
            'circle_product_id' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'quantity' => '',
            'want_priority_id' => '',
            'care_about_circle_id' => '',
            'circle_product_id' => ''
        ];
    }
}
