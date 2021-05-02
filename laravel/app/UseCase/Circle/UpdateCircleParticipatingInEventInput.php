<?php

namespace App\UseCase\Circle;

class UpdateCircleParticipatingInEventInput extends CreateCircleParticipatingInEventInput
{
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'id' => 'required',
        ]);
    }

    protected function attributes(): array
    {
        return array_merge(parent::rules(), [
            'id' => 'サークル番号',
        ]);
    }

    public function getCircleId(): string
    {
        return $this->toArray()['id'];
    }
}
