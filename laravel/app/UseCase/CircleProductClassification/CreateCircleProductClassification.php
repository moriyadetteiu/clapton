<?php

namespace App\UseCase\CircleProductClassification;

use App\Models\CircleProductClassification;
use App\UseCase\UseCase;

class CreateCircleProductClassification extends UseCase
{
    public function execute(CreateCircleProductClassificationInput $input)
    {
        $circleProductClassificationData = $input->toArray();
        $circleProductClassification = CircleProductClassification::create($circleProductClassificationData);
        return $circleProductClassification;
    }
}
