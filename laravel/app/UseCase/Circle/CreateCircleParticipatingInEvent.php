<?php

namespace App\UseCase\Circle;

use Illuminate\Support\Facades\DB;

use App\Models\Circle;
use App\Models\CirclePlacement;
use App\Models\EventDate;
use App\Models\NotParticipationCircle;
use App\UseCase\UseCase;
use App\UseCase\ConflictCircleException;

class CreateCircleParticipatingInEvent extends UseCase
{
    public function execute(CreateCircleParticipatingInEventInput $input)
    {
        $circle = DB::transaction(function () use ($input) {
            $this->validateConflictCircles($input);
            $circle = $this->findOrCreateCircle($input);

            $circlePlacement = $this->findOrCreateCirclePlacement($input, $circle);
            $this->cancelNotParticipateCircleInEvent($input, $circle->id);

            $circle->refresh();

            return $circlePlacement;
        });

        return $circle;
    }

    private function findOrCreateCircle(CreateCircleParticipatingInEventInput $input): Circle
    {
        $circleData = $input->getCircleData();
        if ($circleData['id'] ?? false) {
            return Circle::findOrFail($circleData['id']);
        }

        $alreadyExistsCircle = optional($this->findSamePlacementConsiderCircleName($input))->circle;
        if ($alreadyExistsCircle) {
            return $alreadyExistsCircle;
        }

        return Circle::create($circleData);
    }

    private function findOrCreateCirclePlacement(CreateCircleParticipatingInEventInput $input, Circle $circle): CirclePlacement
    {
        $placementData = $input->getPlacementData();
        $alreadyExistsCirclePlacement = $this->findSamePlacementConsiderCircleName($input);

        if ($alreadyExistsCirclePlacement) {
            return $alreadyExistsCirclePlacement;
        }

        $placementData['circle_id'] = $circle->id;
        return CirclePlacement::create($placementData);
    }

    private function findSamePlacementConsiderCircleName(CreateCircleParticipatingInEventInput $input): ?CirclePlacement
    {
        $circleData = $input->getCircleData();
        $placementData = $input->getPlacementData();
        return (new CirclePlacement())
            ->fill($placementData)
            ->findSamePlacements()
            ->first(fn ($circlePlacement) => $circlePlacement->circle->name === $circleData['name']);
    }

    private function validateConflictCircles(CreateCircleParticipatingInEventInput $input): void
    {
        if ($input->get('force', false)) {
            return;
        }

        $samePlacementConsiderCircleName = $this->findSamePlacementConsiderCircleName($input);

        $circleName = $input->getCircleData()['name'];
        $conflictCirclePlacements = (new CirclePlacement())
            ->fill($input->getPlacementData())
            ->findSamePlacements()
            ->reject(fn ($circlePlacement) => $circlePlacement->id === optional($samePlacementConsiderCircleName)->id);

        $eventId = EventDate::findOrFail($input->getPlacementData()['event_date_id'])->event_id;
        $conflictCircles = Circle::where('name', $circleName)
            ->get()
            ->reject(fn ($circle) => $circle->id === optional(optional($samePlacementConsiderCircleName)->circle)->id)
            ->filter(fn ($circle) => $circle->circlePlacements()->inEvent($eventId)->exists());

        if ($conflictCirclePlacements->isNotEmpty() || $conflictCircles->isNotEmpty()) {
            throw new ConflictCircleException($conflictCircles, $conflictCirclePlacements, '登録済みのリストと競合しています');
        }
    }

    private function cancelNotParticipateCircleInEvent(CreateCircleParticipatingInEventInput $input, string $circleId): void
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
