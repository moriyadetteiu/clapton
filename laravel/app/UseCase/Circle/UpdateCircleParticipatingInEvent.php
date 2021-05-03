<?php

namespace App\UseCase\Circle;

use Illuminate\Support\Facades\DB;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\EventDate;
use App\UseCase\UseCase;

class UpdateCircleParticipatingInEvent extends UseCase
{
    public function execute(UpdateCircleParticipatingInEventInput $input)
    {
        $circle = DB::transaction(function () use ($input) {
            $circle = Circle::findOrFail($input->getCircleId());

            $circleData = $input->getCircleData();
            $circle->update($circleData);

            $placementData = $input->getPlacementData();
            $eventDate = EventDate::findOrFail($placementData['event_date_id']);
            $circlePlacement = $circle->circlePlacements()->inEvent($eventDate->event_id)->firstOrFail();
            $circlePlacement->update($placementData);

            $circle->refresh();

            return $circle;
        });

        return $circle;
    }
}
