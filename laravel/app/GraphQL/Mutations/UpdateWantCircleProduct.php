<?php

namespace App\GraphQL\Mutations;

use App\UseCase\WantCircleProduct\UpdateWantCircleProduct as UpdateWantCircleProductUseCase;
use App\UseCase\WantCircleProduct\UpdateWantCircleProductInput;
use Illuminate\Support\Arr;

class UpdateWantCircleProduct
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new UpdateWantCircleProductInput($data);
        return (new UpdateWantCircleProductUseCase())->execute($input);
    }
}
