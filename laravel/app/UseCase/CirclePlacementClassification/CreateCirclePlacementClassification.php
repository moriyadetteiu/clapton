<?php

namespace App\UseCase\CirclePlacementClassification;

use App\Models\CirclePlacementClassification;
use App\UseCase\UseCase;

class CreateCirclePlacementClassification extends UseCase
{
    public function execute(CreateCirclePlacementClassificationInput $input)
    {
        $circlePlacementClassificationData = $input->toArray();
        $circlePlacementClassification = CirclePlacementClassification::create($circlePlacementClassificationData);
        return $circlePlacementClassification;
    }
}
