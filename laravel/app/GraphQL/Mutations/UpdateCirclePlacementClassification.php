<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CirclePlacementClassification\UpdateCirclePlacementClassification as UpdateCirclePlacementClassificationUseCase;
use App\UseCase\CirclePlacementClassification\UpdateCirclePlacementClassificationInput;
use Illuminate\Support\Arr;

class UpdateCirclePlacementClassification
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new UpdateCirclePlacementClassificationInput($data);
        return (new UpdateCirclePlacementClassificationUseCase())->execute($input);
    }
}
