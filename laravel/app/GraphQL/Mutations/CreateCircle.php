<?php

namespace App\GraphQL\Mutations;

use App\UseCase\Circle\CreateCircle as CreateCircleUseCase;
use App\UseCase\Circle\CreateCircleInput;
use Illuminate\Support\Arr;

class CreateCircle
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateCircleInput($data);
        return (new CreateCircleUseCase())->execute($input);
    }
}
