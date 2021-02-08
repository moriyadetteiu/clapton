<?php

namespace App\UseCase\CircleProductClassification;

class UpdateCircleProductClassificationInput extends CreateCircleProductClassificationInput
{
    protected function rules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required'
        ];
    }
}
