<?php

namespace App\UseCase\CircleProduct;

class UpdateCircleProductInput extends CreateCircleProductInput
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
        return array_merge(parent::attributes(), [
            'id' => '頒布物番号',
            'operation_user_id' => '操作ユーザ番号',
        ]);
    }
}
