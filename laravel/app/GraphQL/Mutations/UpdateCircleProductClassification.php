<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CircleProductClassification\UpdateCircleProductClassification as UpdateCircleProductClassificationUseCase;
use App\UseCase\CircleProductClassification\UpdateCircleProductClassificationInput;
use Illuminate\Support\Arr;

class UpdateCircleProductClassification
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new UpdateCircleProductClassificationInput($data);
        return (new UpdateCircleProductClassificationUseCase())->execute($input);
    }
}
