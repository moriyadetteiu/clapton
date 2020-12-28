<?php

namespace App\UseCase\CirclePlacementClassification;

class UpdateCirclePlacementClassificationInput extends CreateCirclePlacementClassificationInput
{
    protected function rules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'cost' => 'required'
        ];
    }
}
