<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CircleProduct\UpdateCircleProduct as UpdateCircleProductUseCase;
use App\UseCase\CircleProduct\UpdateCircleProductInput;
use Illuminate\Support\Arr;

class UpdateCircleProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new UpdateCircleProductInput($data);
        return (new UpdateCircleProductUseCase())->execute($input);
    }
}
