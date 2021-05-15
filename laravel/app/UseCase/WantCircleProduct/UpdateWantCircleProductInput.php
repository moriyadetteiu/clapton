<?php

namespace App\UseCase\WantCircleProduct;

use App\UseCase\UseCaseInput;

class UpdateWantCircleProductInput extends CreateWantCircleProductInput
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
            'id' => 'ほしいもの番号',
        ]);
    }
}
