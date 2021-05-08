<?php

namespace App\UseCase\CircleProduct;

class UpdateCircleProductInput extends CreateCircleProductInput
{
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'id' => 'required',
        ]);
    }

    protected function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'id' => '頒布物番号',
        ]);
    }
}
