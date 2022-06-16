<?php

namespace App\UseCase\Circle;

class UpdateCircleParticipatingInEventInput extends CreateCircleParticipatingInEventInput
{
    protected function rules(): array
    {
        return array_merge(parent::rules(), [
            'id' => 'required',
            'operation_user_id' => 'required',
        ]);
    }

    protected function attributes(): array
    {
        return array_merge(parent::rules(), [
            'id' => 'サークル番号',
            'operation_user_id' => '操作ユーザ番号',
        ]);
    }

    public function getCircleId(): string
    {
        return $this->toArray()['id'];
    }
}
