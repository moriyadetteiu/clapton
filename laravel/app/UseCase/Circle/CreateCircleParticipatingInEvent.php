<?php

namespace App\UseCase\Circle;

use Illuminate\Support\Facades\DB;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\UseCase\UseCase;

class CreateCircleParticipatingInEvent extends UseCase
{
    public function execute(CreateCircleParticipatingInEventInput $input)
    {
        $circle = DB::transaction(function () use ($input) {
            $circleData = $input->getCircleData();
            $circle = Circle::create($circleData);

            $placementData = $input->getPlacementData();
            $placementData['circle_id'] = $circle->id;
            $circlePlacement = CirclePlacement::create($placementData);

            $circle->refresh();

            return $circlePlacement;
        });

        return $circle;
    }
}
