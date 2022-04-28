<?php

namespace App\UseCase\Favorite;

use App\UseCase\UseCaseInput;

class CreateFavoriteInput extends UseCaseInput
{
    protected function rules(): array
    {
        return [
            'circle_id' => 'required',
            'user_id' => 'required'
        ];
    }

    protected function attributes(): array
    {
        return [
            'circle_id' => 'サークル番号',
            'user_id' => 'ユーザ番号',
        ];
    }
}
