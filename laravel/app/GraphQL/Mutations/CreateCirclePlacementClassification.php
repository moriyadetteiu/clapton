<?php

namespace App\GraphQL\Mutations;

use App\UseCase\CirclePlacementClassification\CreateCirclePlacementClassification as CreateCirclePlacementClassificationUseCase;
use App\UseCase\CirclePlacementClassification\CreateCirclePlacementClassificationInput;
use Illuminate\Support\Arr;

class CreateCirclePlacementClassification
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $data = Arr::except($args, ['directive']);
        $input = new CreateCirclePlacementClassificationInput($data);
        return (new CreateCirclePlacementClassificationUseCase())->execute($input);
    }
}
