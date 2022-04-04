<?php

namespace App\UseCase\WantCircleProduct;

use App\UseCase\UseCaseInput;

class UpdateWantCircleProductInput extends CreateWantCircleProductInput
{
    protected function rules(): array
    {
        $rules = array_merge(parent::rules(), [
            'id' => 'required',
        ]);

        return collect($rules)
            ->except(['circle_product_id', 'user_id'])
            ->toArray();
    }

    protected function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'id' => 'ほしいもの番号',
        ]);
    }
}
