<?php

namespace App\GraphQL\Mutations;

use App\UseCase\WantCircleProduct\CreateWantCircleProduct as CreateWantCircleProductUseCase;
use App\UseCase\WantCircleProduct\CreateWantCircleProductInput;
use Illuminate\Support\Arr;

class CreateWantCircleProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateWantCircleProductInput($data);
        return (new CreateWantCircleProductUseCase())->execute($input);
    }
}
