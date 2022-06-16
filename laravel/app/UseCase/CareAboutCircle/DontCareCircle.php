<?php

namespace App\UseCase\CareAboutCircle;

use App\Models\CareAboutCircle;
use App\Models\Circle;
use App\Models\CirclePlacement;
use App\UseCase\UseCase;

class DontCareCircle extends UseCase
{
    public function execute(DontCareCircleInput $input)
    {
        $careAboutCircle = CareAboutCircle::findOrFail($input->get('id'));
        $circlePlacement = $careAboutCircle->circlePlacement;
        $circle = $circlePlacement->circle;

        $careAboutCircle->delete();

        $isExistsCareAboutCircleOtherUser = $this->isExistsCareAboutCircleOtherUser($circlePlacement);
        $isExistsFavorite = $this->isExistsFavorite($circle);
        if (!$isExistsCareAboutCircleOtherUser && !$isExistsFavorite) {
            $circlePlacement->delete();
            $circle->delete();
        }

        return $careAboutCircle;
    }

    private function isExistsCareAboutCircleOtherUser(CirclePlacement $circlePlacement): bool
    {
        return $circlePlacement->careAboutCircles()->exists();
    }

    private function isExistsFavorite(Circle $circle): bool
    {
        return $circle->favorites()->exists();
    }
}
