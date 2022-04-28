<?php

namespace App\GraphQL\Mutations;

use App\UseCase\Favorite\CreateFavorite as CreateFavoriteUseCase;
use App\UseCase\Favorite\CreateFavoriteInput;
use Illuminate\Support\Arr;

class CreateFavorite
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateFavoriteInput($data);
        return (new CreateFavoriteUseCase())->execute($input);
    }
}
