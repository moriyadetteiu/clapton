<?php

namespace App\UseCase\Circle;

use Illuminate\Support\Facades\DB;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\CareAboutCircle;
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

            $careAboutCircleData = [
                'join_event_id' => $input->getJoinEventId(),
                'circle_id' => $circle->id,
            ];
            $careAboutCircle = CareAboutCircle::create($careAboutCircleData);

            $circle->refresh();

            return $circle;
        });

        return $circle;
    }
}
