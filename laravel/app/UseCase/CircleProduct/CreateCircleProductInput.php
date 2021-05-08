<?php

namespace App\UseCase\CircleProduct;

use App\UseCase\UseCaseInput;

class CreateCircleProductInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'circle_product_classification_id' => 'required',
            'name' => 'required',
            'price' => 'required',
        ];
    }

    protected function attributes(): array
    {
        return [
            'circle_product_classification_id' => '頒布物分類',
            'name' => '頒布物名',
            'price' => '値段',
        ];
    }
}
