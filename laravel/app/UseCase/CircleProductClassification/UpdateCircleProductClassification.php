<?php

namespace App\UseCase\CircleProductClassification;

use App\Models\CircleProductClassification;
use App\UseCase\UseCase;

class UpdateCircleProductClassification extends UseCase
{
    public function execute(UpdateCircleProductClassificationInput $input)
    {
        $circleProductClassificationData = $input->toArray();
        $circleProductClassification = CircleProductClassification::findOrFail($circleProductClassificationData['id']);
        $circleProductClassification->update($circleProductClassificationData);
        $circleProductClassification->refresh();
        return $circleProductClassification;
    }
}
