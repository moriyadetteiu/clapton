<?php

namespace App\UseCase\CirclePlacementClassification;

use App\Models\CirclePlacementClassification;
use App\UseCase\UseCase;

class UpdateCirclePlacementClassification extends UseCase
{
    public function execute(UpdateCirclePlacementClassificationInput $input)
    {
        $circlePlacementClassificationData = $input->toArray();
        $circlePlacementClassification = CirclePlacementClassification::findOrFail($circlePlacementClassificationData['id']);
        $circlePlacementClassification->update($circlePlacementClassificationData);
        $circlePlacementClassification->refresh();
        return $circlePlacementClassification;
    }
}
