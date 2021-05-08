<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CircleProduct\CreateCircleProduct as CreateCircleProductUseCase;
use App\UseCase\CircleProduct\CreateCircleProductInput;
use Illuminate\Support\Arr;

class CreateCircleProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateCircleProductInput($data);
        return (new CreateCircleProductUseCase())->execute($input);
    }
}
