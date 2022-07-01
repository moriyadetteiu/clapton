<?php

namespace App\UseCase\Circle;

use Illuminate\Support\Facades\DB;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\EventDate;
use App\Models\NotParticipationCircle;
use App\UseCase\UseCase;

class CreateCircleParticipatingInEvent extends UseCase
{
    public function execute(CreateCircleParticipatingInEventInput $input)
    {
        $circle = DB::transaction(function () use ($input) {
            $circle = $this->findOrCreate($input);
            $placementData = $input->getPlacementData();
            $placementData['circle_id'] = $circle->id;
            $circlePlacement = CirclePlacement::create($placementData);

            $this->cancelNotParticipateCircleInEvent($input, $circle->id);

            $circle->refresh();

            return $circlePlacement;
        });

        return $circle;
    }

    private function findOrCreate(CreateCircleParticipatingInEventInput $input)
    {
        $circleData = $input->getCircleData();
        if ($circleData['id'] ?? false) {
            return Circle::findOrFail($circleData['id']);
        }
        return Circle::create($circleData);
    }

    private function cancelNotParticipateCircleInEvent(CreateCircleParticipatingInEventInput $input, string $circleId)
    {
        $eventId = EventDate::findOrFail($input->getPlacementData()['event_date_id'])->event_id;
        $isExistsNotParticipationCircle = NotParticipationCircle::where('circle_id', $circleId)
            ->where('event_id', $eventId)
            ->exists();
        if (!$isExistsNotParticipationCircle) {
            return;
        }

        $cancelInput = new CancelNotParticipateCircleInEventInput([
            'event_id' => $eventId,
            'circle_id' => $circleId,
        ]);
        (new CancelNotParticipateCircleInEvent())->execute($cancelInput);
    }
}
