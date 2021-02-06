<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CircleProductClassification\CreateCircleProductClassification as CreateCircleProductClassificationUseCase;
use App\UseCase\CircleProductClassification\CreateCircleProductClassificationInput;
use Illuminate\Support\Arr;

class CreateCircleProductClassification
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateCircleProductClassificationInput($data);
        return (new CreateCircleProductClassificationUseCase())->execute($input);
    }
}
