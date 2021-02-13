<?php

namespace App\UseCase\WantPriority;

class UpdateWantPriorityInput extends CreateWantPriorityInput
{
    protected function rules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required'
        ];
    }
}
