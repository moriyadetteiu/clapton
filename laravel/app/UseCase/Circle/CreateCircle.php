<?php

namespace App\UseCase\Circle;

use App\Models\Circle;
use App\UseCase\UseCase;

class CreateCircle extends UseCase
{
    public function execute(CreateCircleInput $input)
    {
        $circleData = $input->toArray();
        $circle = Circle::create($circleData);
        return $circle;
    }
}
